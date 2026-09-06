<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\TeacherLoginController;
use App\Http\Controllers\SplashController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\LookupController;
use App\Http\Controllers\Student\ReportController;
use App\Http\Controllers\Student\SessionController as StudentSessionController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\LedgerController;
use App\Http\Controllers\Teacher\ReportCardController;
use App\Support\SystemPermissions;
use Illuminate\Support\Facades\Route;

Route::get('/', SplashController::class)->name('splash');

Route::redirect('/login', '/')->name('login');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/admin/login', [AdminLoginController::class, 'store'])->middleware('throttle:5,1');

    Route::get('/teacher/login', [TeacherLoginController::class, 'create'])->name('teacher.login');
    Route::post('/teacher/login', [TeacherLoginController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('/logout', LogoutController::class)
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

Route::middleware(['auth', 'role:admin', 'permission:'.SystemPermissions::AdminOverview])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');

        Route::middleware('permission:'.SystemPermissions::AdminTeachers)->group(function (): void {
            Route::get('/teachers', [TeacherController::class, 'index'])->name('teachers.index');
            Route::get('/teachers/create', [TeacherController::class, 'create'])->name('teachers.create');
            Route::post('/teachers', [TeacherController::class, 'store'])->name('teachers.store');
            Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('teachers.edit');
            Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('teachers.update');
            Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('teachers.destroy');
        });

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::middleware('permission:'.SystemPermissions::AdminProfiles)->group(function (): void {
            Route::get('/users/{user}/profile', [ProfileController::class, 'edit'])->name('users.profile.edit');
            Route::put('/users/{user}/profile', [ProfileController::class, 'update'])->name('users.profile.update');
        });
    });

Route::middleware(['auth', 'role:teacher,admin', 'active'])
    ->prefix('teacher')
    ->name('teacher.')
    ->group(function (): void {
        Route::get('/dashboard', TeacherDashboardController::class)
            ->middleware('permission:'.SystemPermissions::TeacherDashboard)
            ->name('dashboard');

        Route::get('/ledger', LedgerController::class)
            ->middleware('permission:'.SystemPermissions::TeacherLedger)
            ->name('ledger');

        Route::middleware('permission:'.SystemPermissions::TeacherGrades)->group(function (): void {
            Route::get('/form', [ReportCardController::class, 'create'])->name('form');
            Route::post('/form', [ReportCardController::class, 'store'])->name('form.store');
            Route::get('/form/{student}/edit', [ReportCardController::class, 'edit'])->name('form.edit');
            Route::put('/form/{student}', [ReportCardController::class, 'update'])->name('form.update');
            Route::delete('/report-cards/{reportCard}', [ReportCardController::class, 'destroy'])->name('report-cards.destroy');
        });

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
