<?php

namespace App\Models;

use Database\Factories\SubjectScoreFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['report_card_id', 'subject_id', 'marks', 'remarks'])]
class SubjectScore extends Model
{
    /** @use HasFactory<SubjectScoreFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'marks' => 'decimal:2',
        ];
    }

    public function reportCard(): BelongsTo
    {
        return $this->belongsTo(ReportCard::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
