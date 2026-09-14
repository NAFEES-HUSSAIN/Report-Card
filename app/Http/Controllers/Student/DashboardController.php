<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ReportCard;
use App\Models\Student;
use App\Support\ReportCardPresenter;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, ReportCardPresenter $presenter): View
    {
        $student = Student::query()->findOrFail($request->session()->get('student_id'));
        $reportCard = ReportCard::latestForStudent($student);

        $presented = $reportCard
            ? $presenter->fromReportCard($reportCard)
            : $presenter->fromStudentWithoutCard($student);

        return view('student.dashboard', [
            'shellRole' => 'student',
            'student' => $presented,
            'showTranscript' => $request->boolean('transcript'),
        ]);
    }
}
