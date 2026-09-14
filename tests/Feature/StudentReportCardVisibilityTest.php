<?php

use App\Models\AcademicYear;
use App\Models\ReportCard;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Term;

it('shows the newest report card even when it is not the first term', function () {
    $year = AcademicYear::factory()->current()->create();
    $class = SchoolClass::factory()->create(['academic_year_id' => $year->id]);

    $termOne = Term::factory()->create([
        'academic_year_id' => $year->id,
        'name' => 'Term 1',
        'sort_order' => 1,
    ]);

    $termTwo = Term::factory()->create([
        'academic_year_id' => $year->id,
        'name' => 'Term 2',
        'sort_order' => 2,
    ]);

    $student = Student::factory()->create([
        'name' => 'Amina Rahman',
        'index_number' => 'STU-LIVE-1001',
    ]);

    // Grades exist only for Term 2 (common when teachers enter the active term).
    ReportCard::factory()->create([
        'student_id' => $student->id,
        'term_id' => $termTwo->id,
        'school_class_id' => $class->id,
        'average' => 88.5,
        'standing' => 'Distinction',
    ]);

    expect(ReportCard::query()->where('term_id', $termOne->id)->exists())->toBeFalse();

    $this->withSession([
        'student_id' => $student->id,
        'student_name' => $student->name,
    ])->get(route('student.dashboard'))
        ->assertOk()
        ->assertSee('Amina Rahman')
        ->assertSee('88.5%')
        ->assertSee('Term 2')
        ->assertDontSee('No report card yet');
});
