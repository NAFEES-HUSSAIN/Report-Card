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

class ReportController extends Controller
{
    public function __invoke(Request $request, ReportCardPresenter $presenter): View
    {
        $student = Student::query()->findOrFail($request->session()->get('student_id'));

        $year = AcademicYear::current();
        $termId = Term::query()
            ->when($year, fn ($query) => $query->where('academic_year_id', $year->id))
            ->orderBy('sort_order')
            ->value('id');

        $reportCard = ReportCard::query()
            ->with(['student', 'term', 'schoolClass', 'subjectScores.subject'])
            ->where('student_id', $student->id)
            ->when($termId, fn ($query) => $query->where('term_id', $termId))
            ->latest('id')
            ->first();

        $presented = $reportCard
            ? $presenter->fromReportCard($reportCard)
            : $presenter->fromStudentWithoutCard($student);

        return view('student.report', [
            'shellRole' => 'student',
            'student' => $presented,
        ]);
    }
}
