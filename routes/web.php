<?php

use Illuminate\Support\Facades\Route;

$demoStudent = static function (): object {
    return (object) [
        'id' => 1,
        'name' => 'Amina Rahman',
        'index_number' => 'STU-2026-0142',
        'class_name' => 'Form 3A',
        'term' => 'Term 1 2026',
        'standing' => 'Distinction',
        'rank' => 1,
        'average' => 87.5,
        'total_marks' => 437.5,
        'days_present' => 88,
        'days_absent' => 2,
        'total_days' => 90,
        'subjects' => collect([
            (object) ['name' => 'Mathematics', 'marks' => 92, 'remarks' => 'Excellent'],
            (object) ['name' => 'English', 'marks' => 85, 'remarks' => 'Very good'],
            (object) ['name' => 'Science', 'marks' => 88, 'remarks' => 'Strong lab work'],
            (object) ['name' => 'History', 'marks' => 81, 'remarks' => 'Good essays'],
            (object) ['name' => 'Art', 'marks' => 91.5, 'remarks' => 'Creative'],
        ]),
    ];
};

Route::view('/', 'splash')->name('splash');

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', function () {
    return redirect()
        ->route('teacher.dashboard')
        ->with('success', 'Signed in (UI preview — auth not wired yet).');
});

Route::get('/register', fn () => view('auth.register'))->name('register');
Route::post('/register', function () {
    return redirect()
        ->route('teacher.dashboard')
        ->with('success', 'Account created (UI preview — auth not wired yet).');
});

Route::post('/logout', function () {
    return redirect()->route('splash');
})->name('logout');

Route::get('/teacher/dashboard', function () {
    $recentReports = collect([
        (object) ['name' => 'Amina Rahman', 'index_number' => 'STU-2026-0142', 'standing' => 'Distinction', 'average' => 87.5, 'updated_at_human' => '2 hours ago'],
        (object) ['name' => 'Jordan Lee', 'index_number' => 'STU-2026-0148', 'standing' => 'Credit', 'average' => 78.2, 'updated_at_human' => 'Yesterday'],
        (object) ['name' => 'Samira Patel', 'index_number' => 'STU-2026-0151', 'standing' => 'Pass', 'average' => 64.0, 'updated_at_human' => '3 days ago'],
    ]);

    return view('teacher.dashboard', [
        'shellRole' => 'teacher',
        'teacherName' => 'Alex Morgan',
        'totalStudents' => 28,
        'classAverage' => 76.4,
        'passRate' => 92.9,
        'topStanding' => 'Distinction',
        'studentsTrend' => '+2 this week',
        'averageTrend' => '+1.4 pts',
        'passTrend' => 'Stable',
        'standingTrend' => 'Amina R.',
        'recentReports' => $recentReports,
    ]);
})->name('teacher.dashboard');

Route::get('/teacher/form', function () {
    return view('teacher.form', ['shellRole' => 'teacher']);
})->name('teacher.form');

Route::post('/teacher/form', function () {
    return redirect()->route('teacher.ledger')->with('success', 'Grades saved (preview only).');
})->name('teacher.form.store');

Route::get('/teacher/form/{student}/edit', function ($student) use ($demoStudent) {
    $demo = $demoStudent();
    $demo->id = $student;
    $demo->subjects = [
        ['name' => 'Mathematics', 'marks' => 92, 'remarks' => 'Excellent'],
        ['name' => 'English', 'marks' => 85, 'remarks' => 'Very good'],
        ['name' => 'Science', 'marks' => 88, 'remarks' => 'Strong lab work'],
    ];

    return view('teacher.form', [
        'shellRole' => 'teacher',
        'student' => $demo,
    ]);
})->name('teacher.form.edit');

Route::put('/teacher/form/{student}', function () {
    return redirect()->route('teacher.ledger')->with('success', 'Grades updated (preview only).');
})->name('teacher.form.update');

Route::get('/teacher/ledger', function () {
    $students = collect([
        (object) ['id' => 1, 'name' => 'Amina Rahman', 'index_number' => 'STU-2026-0142', 'standing' => 'Distinction', 'rank' => 1, 'average' => 87.5, 'total_marks' => 437.5, 'days_present' => 88, 'total_days' => 90],
        (object) ['id' => 2, 'name' => 'Jordan Lee', 'index_number' => 'STU-2026-0148', 'standing' => 'Credit', 'rank' => 2, 'average' => 78.2, 'total_marks' => 391.0, 'days_present' => 85, 'total_days' => 90],
        (object) ['id' => 3, 'name' => 'Samira Patel', 'index_number' => 'STU-2026-0151', 'standing' => 'Pass', 'rank' => 3, 'average' => 64.0, 'total_marks' => 320.0, 'days_present' => 80, 'total_days' => 90],
    ]);

    return view('teacher.ledger', [
        'shellRole' => 'teacher',
        'students' => $students,
        'totalStudents' => $students->count(),
        'classAverage' => round($students->avg('average'), 1),
        'passRate' => 100.0,
        'topStanding' => 'Distinction',
    ]);
})->name('teacher.ledger');

Route::view('/student/lookup', 'student.lookup')->name('student.lookup');

Route::post('/student/lookup', function () {
    return redirect()
        ->route('student.dashboard')
        ->with('success', 'Lookup matched (preview sample student).');
})->name('student.lookup.submit');

Route::get('/student/dashboard', function () use ($demoStudent) {
    return view('student.dashboard', [
        'shellRole' => 'student',
        'student' => $demoStudent(),
    ]);
})->name('student.dashboard');

Route::get('/student/report', function () use ($demoStudent) {
    return view('student.report', [
        'shellRole' => 'student',
        'student' => $demoStudent(),
    ]);
})->name('student.report');
