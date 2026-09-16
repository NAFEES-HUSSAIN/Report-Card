<?php

use App\Models\ReportCard;
use App\Models\Student;
use App\Models\User;
use App\Support\GradeCatalog;

it('saves an optional teacher remark on a report card without affecting existing marks', function () {
    $teacher = User::factory()->teacher()->create();
    $math = GradeCatalog::subjects()->firstWhere('name', 'Maths');

    $this->actingAs($teacher)
        ->post(route('teacher.form.store'), [
            'index_number' => 'STU-REMARK-001',
            'name' => 'Remark Student',
            'class_name' => 'Form 3A',
            'term' => '1st Term',
            'subjects' => [
                ['subject_id' => $math->id, 'marks' => 75, 'remarks' => 'Good'],
            ],
            'teacher_remark' => 'Excellent attitude in class.',
            'days_present' => 88,
            'days_absent' => 2,
            'total_days' => 90,
        ])
        ->assertRedirect();

    $card = ReportCard::query()->first();

    expect($card)->not->toBeNull()
        ->and($card->teacher_remark)->toBe('Excellent attitude in class.')
        ->and((float) $card->average)->toBe(75.0);
});

it('shows the teacher remark to the student on dashboard and report', function () {
    $teacher = User::factory()->teacher()->create();
    $class = GradeCatalog::resolveClass('Form 3A');
    $term = GradeCatalog::resolveTerm('1st Term');
    $student = Student::factory()->create([
        'index_number' => 'STU-REMARK-002',
        'name' => 'Student With Remark',
    ]);

    ReportCard::factory()->create([
        'student_id' => $student->id,
        'term_id' => $term->id,
        'school_class_id' => $class->id,
        'created_by' => $teacher->id,
        'teacher_remark' => 'Keep improving your handwriting.',
        'average' => 70,
    ]);

    $this->withSession([
        'student_id' => $student->id,
        'student_name' => $student->name,
    ])->get(route('student.dashboard'))
        ->assertOk()
        ->assertSee('Keep improving your handwriting.');

    $this->withSession([
        'student_id' => $student->id,
        'student_name' => $student->name,
    ])->get(route('student.report'))
        ->assertOk()
        ->assertSee('Keep improving your handwriting.');
});
