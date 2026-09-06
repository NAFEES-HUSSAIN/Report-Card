<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreReportCardRequest;
use App\Http\Requests\Teacher\UpdateReportCardRequest;
use App\Models\Enrollment;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\SubjectScore;
use App\Services\ReportCardCalculator;
use App\Support\GradeCatalog;
use App\Support\ReportCardPresenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportCardController extends Controller
{
    public function create(): View
    {
        $this->authorize('create', ReportCard::class);

        return $this->formView();
    }

    public function store(StoreReportCardRequest $request, ReportCardCalculator $calculator): RedirectResponse
    {
        $this->authorize('create', ReportCard::class);

        $reportCard = DB::transaction(function () use ($request, $calculator) {
            $schoolClass = GradeCatalog::resolveClass($request->string('class_name')->toString());
            $term = GradeCatalog::resolveTerm($request->string('term')->toString());

            $student = Student::query()->updateOrCreate(
                ['index_number' => $request->string('index_number')->toString()],
                ['name' => $request->string('name')->toString()],
            );

            Enrollment::query()->updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_year_id' => $schoolClass->academic_year_id,
                ],
                [
                    'school_class_id' => $schoolClass->id,
                    'enrolled_at' => now()->toDateString(),
                ],
            );

            $reportCard = ReportCard::query()->updateOrCreate(
                [
                    'student_id' => $student->id,
                    'term_id' => $term->id,
                ],
                [
                    'school_class_id' => $schoolClass->id,
                    'created_by' => $request->user()?->id,
                    'days_present' => $request->integer('days_present'),
                    'days_absent' => $request->integer('days_absent'),
                    'total_days' => $request->integer('total_days'),
                ],
            );

            $this->syncScores($reportCard, $request->validated('subjects'));
            $calculator->refreshTotalsAndRanks($reportCard);

            return $reportCard;
        });

        return redirect()
            ->route('teacher.ledger', [
                'school_class_id' => $reportCard->school_class_id,
                'term_id' => $reportCard->term_id,
            ])
            ->with('success', 'Report card saved successfully.');
    }

    public function edit(Student $student, ReportCardPresenter $presenter): View
    {
        $reportCard = ReportCard::query()
            ->with(['student', 'term', 'schoolClass', 'subjectScores.subject'])
            ->where('student_id', $student->id)
            ->latest('id')
            ->first();

        if ($reportCard !== null) {
            $this->authorize('update', $reportCard);
        } else {
            $this->authorize('create', ReportCard::class);
        }

        $presented = $reportCard
            ? $presenter->fromReportCard($reportCard)
            : $presenter->fromStudentWithoutCard($student);

        return $this->formView($presented, $reportCard);
    }

    public function update(
        UpdateReportCardRequest $request,
        Student $student,
        ReportCardCalculator $calculator,
    ): RedirectResponse {
        $existing = ReportCard::query()
            ->where('student_id', $student->id)
            ->latest('id')
            ->first();

        if ($existing !== null) {
            $this->authorize('update', $existing);
        } else {
            $this->authorize('create', ReportCard::class);
        }

        $reportCard = DB::transaction(function () use ($request, $student, $calculator) {
            $schoolClass = GradeCatalog::resolveClass($request->string('class_name')->toString());
            $term = GradeCatalog::resolveTerm($request->string('term')->toString());

            $student->update([
                'index_number' => $request->string('index_number')->toString(),
                'name' => $request->string('name')->toString(),
            ]);

            Enrollment::query()->updateOrCreate(
                [
                    'student_id' => $student->id,
                    'academic_year_id' => $schoolClass->academic_year_id,
                ],
                [
                    'school_class_id' => $schoolClass->id,
                    'enrolled_at' => now()->toDateString(),
                ],
            );

            $reportCard = ReportCard::query()->updateOrCreate(
                [
                    'student_id' => $student->id,
                    'term_id' => $term->id,
                ],
                [
                    'school_class_id' => $schoolClass->id,
                    'created_by' => $request->user()?->id,
                    'days_present' => $request->integer('days_present'),
                    'days_absent' => $request->integer('days_absent'),
                    'total_days' => $request->integer('total_days'),
                ],
            );

            $this->syncScores($reportCard, $request->validated('subjects'));
            $calculator->refreshTotalsAndRanks($reportCard);

            return $reportCard;
        });

        return redirect()
            ->route('teacher.ledger', [
                'school_class_id' => $reportCard->school_class_id,
                'term_id' => $reportCard->term_id,
            ])
            ->with('success', 'Report card updated successfully.');
    }

    /**
     * @param  array<int, array{subject_id: int|string, marks: mixed, remarks?: string|null}>  $subjects
     */
    private function syncScores(ReportCard $reportCard, array $subjects): void
    {
        $reportCard->subjectScores()->delete();

        foreach ($subjects as $row) {
            SubjectScore::query()->create([
                'report_card_id' => $reportCard->id,
                'subject_id' => (int) $row['subject_id'],
                'marks' => $row['marks'],
                'remarks' => $row['remarks'] ?? null,
            ]);
        }
    }

    private function formView(?object $student = null, ?ReportCard $reportCard = null): View
    {
        $availableSubjects = GradeCatalog::subjects();

        return view('teacher.form', [
            'shellRole' => 'teacher',
            'student' => $student,
            'reportCard' => $reportCard,
            'termOptions' => GradeCatalog::termNames(),
            'availableSubjects' => $availableSubjects,
            'selectedTerm' => old('term', data_get($student, 'term')),
            'selectedClassName' => old('class_name', data_get($student, 'class_name')),
        ]);
    }
}
