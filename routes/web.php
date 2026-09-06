<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SplashController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\LookupController;
use App\Http\Controllers\Student\ReportController;
use App\Http\Controllers\Student\SessionController as StudentSessionController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\LedgerController;
use App\Http\Controllers\Teacher\ReportCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', SplashController::class)->name('splash');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:5,1');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/student/lookup', [LookupController::class, 'create'])->name('student.lookup');
Route::post('/student/lookup', [LookupController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('student.lookup.submit');

Route::post('/student/logout', [StudentSessionController::class, 'destroy'])
    ->middleware('student.session')
    ->name('student.logout');

Route::middleware('student.session')->group(function (): void {
    Route::get('/student/dashboard', StudentDashboardController::class)->name('student.dashboard');
    Route::get('/student/report', ReportController::class)->name('student.report');
});

Route::middleware(['auth', 'role:teacher,admin'])->prefix('teacher')->name('teacher.')->group(function (): void {
    Route::get('/dashboard', TeacherDashboardController::class)->name('dashboard');
    Route::get('/ledger', LedgerController::class)->name('ledger');

    Route::get('/form', [ReportCardController::class, 'create'])->name('form');
    Route::post('/form', [ReportCardController::class, 'store'])->name('form.store');
    Route::get('/form/{student}/edit', [ReportCardController::class, 'edit'])->name('form.edit');
    Route::put('/form/{student}', [ReportCardController::class, 'update'])->name('form.update');
});
