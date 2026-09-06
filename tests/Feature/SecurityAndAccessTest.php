<?php

use App\Enums\UserRole;
use App\Models\Student;
use App\Models\User;

it('redirects guests away from teacher routes', function () {
    $this->get(route('teacher.dashboard'))->assertRedirect(route('login'));
    $this->get(route('teacher.form'))->assertRedirect(route('login'));
    $this->get(route('teacher.ledger'))->assertRedirect(route('login'));
});

it('blocks students from teacher routes even with a student session', function () {
    $student = Student::factory()->create();

    $this->withSession([
        'student_id' => $student->id,
        'student_name' => $student->name,
    ])->get(route('teacher.dashboard'))->assertRedirect(route('login'));
});

it('allows teachers into the teacher workspace', function () {
    $teacher = User::factory()->teacher()->create();

    $this->actingAs($teacher)
        ->get(route('teacher.dashboard'))
        ->assertOk();
});

it('signs a student out of the portal session', function () {
    $student = Student::factory()->create();

    $this->withSession([
        'student_id' => $student->id,
        'student_name' => $student->name,
    ])->post(route('student.logout'))
        ->assertRedirect(route('student.lookup'));

    expect(session('student_id'))->toBeNull();
});

it('seeds only teacher and admin roles for staff users', function () {
    expect(UserRole::cases())->toHaveCount(2);
});
