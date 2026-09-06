<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\LookupRequest;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LookupController extends Controller
{
    public function create(): View
    {
        return view('student.lookup');
    }

    public function store(LookupRequest $request): RedirectResponse
    {
        $student = Student::query()
            ->where('index_number', $request->string('index_number')->toString())
            ->firstOrFail();

        $request->session()->put('student_id', $student->id);
        $request->session()->put('student_name', $student->name);
        $request->session()->forget('student_report_card_id');

        return redirect()
            ->route('student.dashboard')
            ->with('success', 'Welcome, '.$student->name.'.');
    }
}
