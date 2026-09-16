<?php

use App\Models\ReportCard;
use App\Models\Student;
use App\Models\User;
use App\Support\GradeCatalog;

it('searches the ledger by student name and paginates results', function () {
    $teacher = User::factory()->teacher()->create();
    $class = GradeCatalog::resolveClass('Form 3A');
    $term = GradeCatalog::resolveTerm('1st Term');

    $match = Student::factory()->create([
        'name' => 'Zara UniqueSearch',
        'index_number' => 'STU-SEARCH-001',
    ]);
    $other = Student::factory()->create([
        'name' => 'Other Student',
        'index_number' => 'STU-SEARCH-002',
    ]);

    ReportCard::factory()->create([
        'student_id' => $match->id,
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
        'average' => 88,
        'rank' => 1,
    ]);
    ReportCard::factory()->create([
        'student_id' => $other->id,
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
        'average' => 70,
        'rank' => 2,
    ]);

    $this->actingAs($teacher)
        ->get(route('teacher.ledger', [
            'school_class_id' => $class->id,
            'term_id' => $term->id,
            'q' => 'UniqueSearch',
        ]))
        ->assertOk()
        ->assertSee('Zara UniqueSearch')
        ->assertDontSee('Other Student');
});

it('sorts the ledger by student name', function () {
    $teacher = User::factory()->teacher()->create();
    $class = GradeCatalog::resolveClass('Form 3B');
    $term = GradeCatalog::resolveTerm('1st Term');

    $first = Student::factory()->create(['name' => 'Aaron Sort', 'index_number' => 'STU-SORT-001']);
    $second = Student::factory()->create(['name' => 'Zoe Sort', 'index_number' => 'STU-SORT-002']);

    ReportCard::factory()->create([
        'student_id' => $first->id,
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
        'average' => 70,
        'rank' => 2,
    ]);
    ReportCard::factory()->create([
        'student_id' => $second->id,
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
        'average' => 90,
        'rank' => 1,
    ]);

    $response = $this->actingAs($teacher)
        ->get(route('teacher.ledger', [
            'school_class_id' => $class->id,
            'term_id' => $term->id,
            'sort' => 'name',
            'direction' => 'asc',
        ]))
        ->assertOk();

    $response->assertSeeInOrder(['Aaron Sort', 'Zoe Sort']);
});

it('deletes a report card from the ledger', function () {
    $teacher = User::factory()->teacher()->create();
    $class = GradeCatalog::resolveClass('Form 3C');
    $term = GradeCatalog::resolveTerm('2nd Term');
    $student = Student::factory()->create();

    $card = ReportCard::factory()->create([
        'student_id' => $student->id,
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
    ]);

    $this->actingAs($teacher)
        ->delete(route('teacher.report-cards.destroy', $card))
        ->assertRedirect(route('teacher.ledger', [
            'school_class_id' => $class->id,
            'term_id' => $term->id,
        ]));

    expect(ReportCard::query()->whereKey($card->id)->exists())->toBeFalse()
        ->and(Student::query()->whereKey($student->id)->exists())->toBeFalse();
});
