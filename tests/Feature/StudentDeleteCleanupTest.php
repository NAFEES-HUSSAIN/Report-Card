<?php

use App\Models\Enrollment;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\User;
use App\Support\GradeCatalog;

it('removes the student from lookup after their last report card is deleted', function () {
    $teacher = User::factory()->teacher()->create();
    $class = GradeCatalog::resolveClass('Form 3C');
    $term = GradeCatalog::resolveTerm('2nd Term');
    $student = Student::factory()->create([
        'index_number' => 'STU-DEL-0001',
        'name' => 'Deleted Student',
    ]);

    Enrollment::query()->create([
        'student_id' => $student->id,
        'school_class_id' => $class->id,
        'academic_year_id' => $class->academic_year_id,
        'enrolled_at' => now()->toDateString(),
    ]);

    $card = ReportCard::factory()->create([
        'student_id' => $student->id,
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
    ]);

    $this->actingAs($teacher)
        ->delete(route('teacher.report-cards.destroy', $card))
        ->assertRedirect();

    expect(ReportCard::query()->whereKey($card->id)->exists())->toBeFalse()
        ->and(Student::query()->whereKey($student->id)->exists())->toBeFalse()
        ->and(Student::withTrashed()->whereKey($student->id)->exists())->toBeTrue()
        ->and(Enrollment::query()->where('student_id', $student->id)->exists())->toBeFalse();

    $this->from(route('student.lookup'))
        ->post(route('student.lookup.submit'), [
            'index_number' => 'STU-DEL-0001',
        ])
        ->assertRedirect(route('student.lookup'))
        ->assertSessionHasErrors('index_number');
});

it('keeps the student lookupable when another report card still exists', function () {
    $teacher = User::factory()->teacher()->create();
    $class = GradeCatalog::resolveClass('Form 3C');
    $termOne = GradeCatalog::resolveTerm('1st Term');
    $termTwo = GradeCatalog::resolveTerm('2nd Term');
    $student = Student::factory()->create([
        'index_number' => 'STU-KEEP-0002',
        'name' => 'Multi Term Student',
    ]);

    $firstCard = ReportCard::factory()->create([
        'student_id' => $student->id,
        'school_class_id' => $class->id,
        'term_id' => $termOne->id,
        'created_by' => $teacher->id,
    ]);

    ReportCard::factory()->create([
        'student_id' => $student->id,
        'school_class_id' => $class->id,
        'term_id' => $termTwo->id,
        'created_by' => $teacher->id,
    ]);

    $this->actingAs($teacher)
        ->delete(route('teacher.report-cards.destroy', $firstCard))
        ->assertRedirect();

    expect(Student::query()->whereKey($student->id)->exists())->toBeTrue();

    $this->post(route('student.lookup.submit'), [
        'index_number' => 'STU-KEEP-0002',
    ])->assertRedirect(route('student.dashboard'));
});

it('rejects lookup for students that have no report cards', function () {
    Student::factory()->create([
        'index_number' => 'STU-ORPHAN-0003',
        'name' => 'Orphan Student',
    ]);

    $this->from(route('student.lookup'))
        ->post(route('student.lookup.submit'), [
            'index_number' => 'STU-ORPHAN-0003',
        ])
        ->assertRedirect(route('student.lookup'))
        ->assertSessionHasErrors('index_number');
});

it('restores a soft-deleted student when a teacher saves the same index again', function () {
    $teacher = User::factory()->teacher()->create();
    $subjects = GradeCatalog::subjects();
    $math = $subjects->firstWhere('name', 'Maths');

    $student = Student::factory()->create([
        'index_number' => 'STU-RESTORE-0004',
        'name' => 'Old Name',
    ]);
    $student->delete();

    $this->actingAs($teacher)
        ->post(route('teacher.form.store'), [
            'index_number' => 'STU-RESTORE-0004',
            'name' => 'Restored Name',
            'class_name' => 'Form 3A',
            'term' => '1st Term',
            'subjects' => [
                ['subject_id' => $math->id, 'marks' => 75, 'remarks' => null],
            ],
            'days_present' => 80,
            'days_absent' => 0,
            'total_days' => 80,
        ])
        ->assertRedirect();

    $restored = Student::query()->where('index_number', 'STU-RESTORE-0004')->first();

    expect($restored)->not->toBeNull()
        ->and($restored->id)->toBe($student->id)
        ->and($restored->name)->toBe('Restored Name')
        ->and($restored->trashed())->toBeFalse();
});
