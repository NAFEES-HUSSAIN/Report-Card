<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\Standing;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\LedgerIndexRequest;
use App\Models\AcademicYear;
use App\Models\ReportCard;
use App\Models\SchoolClass;
use App\Models\Term;
use App\Support\ReportCardPresenter;
use App\Support\TableSort;
use Illuminate\View\View;

class LedgerController extends Controller
{
    public function __invoke(LedgerIndexRequest $request, ReportCardPresenter $presenter): View
    {
        $this->authorize('viewAny', ReportCard::class);

        $year = AcademicYear::current();
        $search = $request->string('q')->trim()->toString();

        $schoolClassId = $request->integer('school_class_id') ?: SchoolClass::query()
            ->when($year, fn ($query) => $query->where('academic_year_id', $year->id))
            ->orderBy('name')
            ->value('id');

        $termId = $request->integer('term_id') ?: Term::query()
            ->when($year, fn ($query) => $query->where('academic_year_id', $year->id))
            ->orderBy('sort_order')
            ->value('id');

        $sortColumns = [
            'rank' => 'report_cards.rank',
            'index' => 'students.index_number',
            'name' => 'students.name',
            'standing' => 'report_cards.standing',
            'average' => 'report_cards.average',
            'total' => 'report_cards.total_marks',
        ];

        [$sort, $direction] = TableSort::from($request, $sortColumns, 'rank');

        $baseQuery = ReportCard::query()
            ->select('report_cards.*')
            ->join('students', 'students.id', '=', 'report_cards.student_id')
            ->with(['student', 'term', 'schoolClass', 'subjectScores.subject'])
            ->when($schoolClassId, fn ($query) => $query->where('report_cards.school_class_id', $schoolClassId))
            ->when($termId, fn ($query) => $query->where('report_cards.term_id', $termId))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner
                        ->where('students.name', 'like', "%{$search}%")
                        ->orWhere('students.index_number', 'like', "%{$search}%");
                });
            });

        $statsQuery = ReportCard::query()
            ->when($schoolClassId, fn ($query) => $query->where('school_class_id', $schoolClassId))
            ->when($termId, fn ($query) => $query->where('term_id', $termId))
            ->when($search !== '', function ($query) use ($search): void {
                $query->whereHas('student', function ($studentQuery) use ($search): void {
                    $studentQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('index_number', 'like', "%{$search}%");
                });
            });

        $totalStudents = (clone $statsQuery)->count();
        $classAverage = round((float) ((clone $statsQuery)->avg('average') ?? 0), 1);
        $passCount = (clone $statsQuery)->where('standing', '!=', Standing::Fail->value)->count();
        $passRate = $totalStudents > 0 ? round(($passCount / $totalStudents) * 100, 1) : 0.0;
        $topStanding = (clone $statsQuery)->orderByDesc('average')->value('standing');
        $topStanding = $topStanding instanceof Standing ? $topStanding->value : ($topStanding ?: '—');

        $students = TableSort::apply($baseQuery, $request, $sortColumns, 'rank')
            ->paginate(10)
            ->withQueryString()
            ->through(fn (ReportCard $card) => $presenter->fromReportCard($card));

        return view('teacher.ledger', [
            'shellRole' => 'teacher',
            'students' => $students,
            'totalStudents' => $totalStudents,
            'classAverage' => $classAverage,
            'passRate' => $passRate,
            'topStanding' => $topStanding,
            'schoolClasses' => SchoolClass::query()
                ->when($year, fn ($query) => $query->where('academic_year_id', $year->id))
                ->orderBy('name')
                ->get(),
            'terms' => Term::query()
                ->when($year, fn ($query) => $query->where('academic_year_id', $year->id))
                ->orderBy('sort_order')
                ->get(),
            'selectedClassId' => $schoolClassId,
            'selectedTermId' => $termId,
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }
}
