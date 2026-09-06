<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\Term;
use App\Support\ReportCardPresenter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, ReportCardPresenter $presenter): View
    {
        $student = Student::query()->findOrFail($request->session()->get('student_id'));
        $reportCard = $this->latestReportCard($student);

        $presented = $reportCard
            ? $presenter->fromReportCard($reportCard)
            : $presenter->fromStudentWithoutCard($student);

        return view('student.dashboard', [
            'shellRole' => 'student',
            'student' => $presented,
            'showTranscript' => $request->boolean('transcript'),
        ]);
    }

    private function latestReportCard(Student $student): ?ReportCard
    {
        $year = AcademicYear::current();
        $termId = Term::query()
            ->when($year, fn ($query) => $query->where('academic_year_id', $year->id))
            ->orderBy('sort_order')
            ->value('id');

        return ReportCard::query()
            ->with(['student', 'term', 'schoolClass', 'subjectScores.subject'])
            ->where('student_id', $student->id)
            ->when($termId, fn ($query) => $query->where('term_id', $termId))
            ->latest('id')
            ->first();
    }
}
