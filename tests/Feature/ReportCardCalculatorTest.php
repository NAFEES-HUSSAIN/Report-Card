<?php

use App\Enums\Standing;
use App\Models\ReportCard;
use App\Models\Subject;
use App\Models\SubjectScore;
use App\Services\ReportCardCalculator;

it('computes average total and standing from subject scores', function () {
    $card = ReportCard::factory()->create([
        'average' => 0,
        'total_marks' => 0,
        'standing' => Standing::Fail,
    ]);

    $math = Subject::factory()->create(['code' => 'MATH1', 'name' => 'Mathematics']);
    $english = Subject::factory()->create(['code' => 'ENG1', 'name' => 'English']);

    SubjectScore::factory()->create([
        'report_card_id' => $card->id,
        'subject_id' => $math->id,
        'marks' => 90,
    ]);
    SubjectScore::factory()->create([
        'report_card_id' => $card->id,
        'subject_id' => $english->id,
        'marks' => 70,
    ]);

    $updated = app(ReportCardCalculator::class)->refreshTotals($card);

    expect((float) $updated->average)->toBe(80.0)
        ->and((float) $updated->total_marks)->toBe(160.0)
        ->and($updated->standing)->toBe(Standing::Distinction);
});
