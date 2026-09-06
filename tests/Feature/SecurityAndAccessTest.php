<?php

use App\Enums\UserRole;
use App\Models\Student;
use App\Models\User;
use App\Support\SystemPermissions;
use Database\Seeders\PermissionSeeder;

it('redirects guests away from teacher routes', function () {
    $this->get(route('teacher.dashboard'))->assertRedirect(route('splash'));
    $this->get(route('teacher.form'))->assertRedirect(route('splash'));
    $this->get(route('teacher.ledger'))->assertRedirect(route('splash'));
});

it('blocks students from teacher routes even with a student session', function () {
    $student = Student::factory()->create();

    $this->withSession([
        'student_id' => $student->id,
        'student_name' => $student->name,
    ])->get(route('teacher.dashboard'))->assertRedirect(route('splash'));
});

it('allows teachers into the teacher workspace', function () {
    $teacher = User::factory()->teacher()->create();

    $this->actingAs($teacher)
        ->get(route('teacher.dashboard'))
        ->assertOk();
});

it('blocks teachers without grades permission from the form', function () {
    (new PermissionSeeder)->run();

    $teacher = User::factory()->teacher()->create();
    $teacher->syncPermissionKeys([SystemPermissions::TeacherDashboard]);

    $this->actingAs($teacher)
        ->get(route('teacher.form'))
        ->assertForbidden();
});

it('allows admins into the admin dashboard and teacher portal', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();

    $this->actingAs($admin)
        ->get(route('teacher.dashboard'))
        ->assertOk();
});

it('lets an admin create a teacher with permissions', function () {
    (new PermissionSeeder)->run();
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->post(route('admin.teachers.store'), [
            'name' => 'New Teacher',
            'username' => 'new.teacher',
            'email' => 'new.teacher@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'is_active' => '1',
            'permissions' => [
                SystemPermissions::TeacherDashboard,
                SystemPermissions::TeacherGrades,
            ],
        ])
        ->assertRedirect(route('admin.teachers.index'));

    $teacher = User::query()->where('email', 'new.teacher@example.com')->first();

    expect($teacher)->not->toBeNull()
        ->and($teacher->role)->toBe(UserRole::Teacher)
        ->and($teacher->hasPermission(SystemPermissions::TeacherDashboard))->toBeTrue()
        ->and($teacher->hasPermission(SystemPermissions::TeacherGrades))->toBeTrue()
        ->and($teacher->hasPermission(SystemPermissions::TeacherLedger))->toBeFalse();
});

it('lets an admin delete a teacher from the directory', function () {
    $admin = User::factory()->admin()->create();
    $teacher = User::factory()->teacher()->create([
        'email' => 'remove.me@example.com',
    ]);

    $this->actingAs($admin)
        ->delete(route('admin.teachers.destroy', $teacher))
        ->assertRedirect(route('admin.teachers.index'));

    expect(User::query()->where('email', 'remove.me@example.com')->exists())->toBeFalse();
});

it('sorts and filters the teacher directory', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->teacher()->create(['name' => 'Alpha Teacher', 'username' => 'alpha.t']);
    User::factory()->teacher()->create(['name' => 'Zulu Teacher', 'username' => 'zulu.t']);

    $this->actingAs($admin)
        ->get(route('admin.teachers.index', ['sort' => 'name', 'direction' => 'desc', 'q' => 'Zulu']))
        ->assertOk()
        ->assertSee('Zulu Teacher')
        ->assertDontSee('Alpha Teacher');
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
