<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget(['student_id', 'student_name', 'student_report_card_id']);

        return redirect()
            ->route('student.lookup')
            ->with('success', 'You have been signed out of the student portal.');
    }
}
