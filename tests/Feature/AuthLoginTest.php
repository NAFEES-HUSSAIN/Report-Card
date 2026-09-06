<?php

use App\Models\User;

it('shows the teacher login page', function () {
    $this->get(route('teacher.login'))->assertOk();
});

it('shows the admin login page', function () {
    $this->get(route('admin.login'))->assertOk();
});

it('redirects legacy login to the splash', function () {
    $this->get(route('login'))->assertRedirect('/');
});

it('authenticates a teacher and redirects to the dashboard', function () {
    $teacher = User::factory()->teacher()->create([
        'email' => 'teacher@example.com',
        'username' => 'teacher.example',
        'password' => 'password',
    ]);

    $this->post(route('teacher.login'), [
        'login' => $teacher->email,
        'password' => 'password',
    ])->assertRedirect(route('teacher.dashboard'));

    $this->assertAuthenticatedAs($teacher);
});

it('authenticates an admin and redirects to the admin dashboard', function () {
    $admin = User::factory()->admin()->create([
        'email' => 'admin@example.com',
        'username' => 'principal.example',
        'password' => 'password',
    ]);

    $this->post(route('admin.login'), [
        'login' => 'principal.example',
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($admin);
});

it('rejects invalid teacher credentials', function () {
    User::factory()->teacher()->create([
        'email' => 'teacher@example.com',
        'password' => 'password',
    ]);

    $this->from(route('teacher.login'))
        ->post(route('teacher.login'), [
            'login' => 'teacher@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('teacher.login'))
        ->assertSessionHasErrors('login');

    $this->assertGuest();
});

it('blocks inactive teachers from signing in', function () {
    User::factory()->teacher()->inactive()->create([
        'email' => 'inactive@example.com',
        'password' => 'password',
    ]);

    $this->from(route('teacher.login'))
        ->post(route('teacher.login'), [
            'login' => 'inactive@example.com',
            'password' => 'password',
        ])
        ->assertRedirect(route('teacher.login'))
        ->assertSessionHasErrors('login');

    $this->assertGuest();
});

it('does not expose public teacher registration', function () {
    $this->get('/register')->assertNotFound();
    $this->post('/register', [])->assertNotFound();
});
