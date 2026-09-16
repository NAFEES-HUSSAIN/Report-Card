<?php

use App\Enums\Standing;
use App\Models\ReportCard;
use App\Models\Student;
use App\Models\User;
use App\Services\ReportCardCalculator;
use App\Support\GradeCatalog;

it('allows a teacher to create a report card with typed class and fixed term', function () {
    $teacher = User::factory()->teacher()->create();
    $subjects = GradeCatalog::subjects();
    $math = $subjects->firstWhere('name', 'Maths');
    $english = $subjects->firstWhere('name', 'English');

    $this->actingAs($teacher)
        ->post(route('teacher.form.store'), [
            'index_number' => 'STU-2026-0999',
            'name' => 'Test Student',
            'class_name' => 'Form 3A',
            'term' => '1st Term',
            'subjects' => [
                ['subject_id' => $math->id, 'marks' => 90, 'remarks' => 'Excellent'],
                ['subject_id' => $english->id, 'marks' => 80, 'remarks' => 'Good'],
            ],
            'days_present' => 88,
            'days_absent' => 2,
            'total_days' => 90,
        ])
        ->assertRedirect();

    $card = ReportCard::query()->first();
    expect($card)->not->toBeNull()
        ->and((float) $card->average)->toBe(85.0)
        ->and($card->standing)->toBe(Standing::Distinction)
        ->and($card->schoolClass->name)->toBe('Form 3A')
        ->and($card->term->name)->toBe('1st Term')
        ->and($card->rank)->toBe(1);
});

it('recalculates dense ranks for a class term', function () {
    $teacher = User::factory()->teacher()->create();
    $class = GradeCatalog::resolveClass('Form 4A');
    $term = GradeCatalog::resolveTerm('2nd Term');

    $first = ReportCard::factory()->create([
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
        'average' => 90,
        'standing' => Standing::Distinction,
    ]);
    $second = ReportCard::factory()->create([
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
        'average' => 80,
        'standing' => Standing::Distinction,
    ]);
    $tied = ReportCard::factory()->create([
        'school_class_id' => $class->id,
        'term_id' => $term->id,
        'created_by' => $teacher->id,
        'average' => 80,
        'standing' => Standing::Distinction,
    ]);

    app(ReportCardCalculator::class)->refreshRanksForClassTerm($class->id, $term->id);

    expect($first->refresh()->rank)->toBe(1)
        ->and($second->refresh()->rank)->toBe(2)
        ->and($tied->refresh()->rank)->toBe(2);
});

it('lists catalog subjects alphabetically', function () {
    $names = GradeCatalog::subjects()->pluck('name')->all();

    expect($names)->toBe([
        'Art',
        'Commerce',
        'English',
        'English Lit',
        'Health & PED',
        'History',
        'ICT',
        'Islam',
        'Maths',
        'Science',
        'Sinhala',
        'Tamil',
        'Tamil Lit',
    ]);
});

it('allows a teacher to update an existing report card', function () {
    $teacher = User::factory()->teacher()->create();
    $subjects = GradeCatalog::subjects();
    $math = $subjects->firstWhere('name', 'Maths');

    $this->actingAs($teacher)
        ->post(route('teacher.form.store'), [
            'index_number' => 'STU-2026-0888',
            'name' => 'Update Me',
            'class_name' => 'Form 3B',
            'term' => '1st Term',
            'subjects' => [
                ['subject_id' => $math->id, 'marks' => 70, 'remarks' => 'OK'],
            ],
            'days_present' => 80,
            'days_absent' => 0,
            'total_days' => 80,
            'teacher_remark' => 'First remark',
        ])
        ->assertRedirect();

    $student = Student::query()->where('index_number', 'STU-2026-0888')->first();
    expect($student)->not->toBeNull();

    $this->actingAs($teacher)
        ->put(route('teacher.form.update', $student), [
            'index_number' => 'STU-2026-0888',
            'name' => 'Update Me Junior',
            'class_name' => 'Form 3B',
            'term' => '1st Term',
            'subjects' => [
                ['subject_id' => $math->id, 'marks' => 92, 'remarks' => 'Excellent'],
            ],
            'days_present' => 85,
            'days_absent' => 0,
            'total_days' => 85,
            'teacher_remark' => 'Improved a lot',
        ])
        ->assertRedirect();

    $card = ReportCard::query()->where('student_id', $student->id)->first();

    expect($student->refresh()->name)->toBe('Update Me Junior')
        ->and((float) $card->average)->toBe(92.0)
        ->and($card->teacher_remark)->toBe('Improved a lot')
        ->and($card->subjectScores()->first()->remarks)->toBe('Excellent');
});
