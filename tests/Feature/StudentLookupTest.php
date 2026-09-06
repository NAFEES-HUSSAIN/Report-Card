<?php

use App\Models\Student;

it('looks up a student by index number and stores session', function () {
    $student = Student::factory()->create([
        'index_number' => 'STU-2026-0142',
        'name' => 'Amina Rahman',
    ]);

    $this->post(route('student.lookup.submit'), [
        'index_number' => $student->index_number,
    ])->assertRedirect(route('student.dashboard'));

    $this->assertEquals($student->id, session('student_id'));
});

it('rejects unknown index numbers', function () {
    $this->from(route('student.lookup'))
        ->post(route('student.lookup.submit'), [
            'index_number' => 'MISSING-0001',
        ])
        ->assertRedirect(route('student.lookup'))
        ->assertSessionHasErrors('index_number');
});

it('blocks the student dashboard without a session', function () {
    $this->get(route('student.dashboard'))
        ->assertRedirect(route('student.lookup'));
});
