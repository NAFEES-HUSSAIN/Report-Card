<?php

use App\Models\User;

it('shows the login page', function () {
    $this->get(route('login'))->assertOk();
});

it('authenticates a teacher and redirects to the dashboard', function () {
    $teacher = User::factory()->teacher()->create([
        'email' => 'teacher@example.com',
        'password' => 'password',
    ]);

    $this->post(route('login'), [
        'email' => $teacher->email,
        'password' => 'password',
    ])->assertRedirect(route('teacher.dashboard'));

    $this->assertAuthenticatedAs($teacher);
});

it('rejects invalid credentials', function () {
    User::factory()->teacher()->create([
        'email' => 'teacher@example.com',
        'password' => 'password',
    ]);

    $this->from(route('login'))
        ->post(route('login'), [
            'email' => 'teacher@example.com',
            'password' => 'wrong-password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('does not expose public teacher registration', function () {
    $this->get('/register')->assertNotFound();
    $this->post('/register', [])->assertNotFound();
});
