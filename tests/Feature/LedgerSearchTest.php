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
