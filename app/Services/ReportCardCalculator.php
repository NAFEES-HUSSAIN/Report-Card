<?php

namespace App\Services;

use App\Enums\Standing;
use App\Models\ReportCard;
use Illuminate\Support\Facades\DB;

class ReportCardCalculator
{
    /**
     * Recalculate average, total marks, and standing from subject scores.
     */
    public function refreshTotals(ReportCard $reportCard): ReportCard
    {
        $reportCard->loadMissing('subjectScores');

        $scores = $reportCard->subjectScores;
        $count = $scores->count();
        $total = (float) $scores->sum('marks');
        $average = $count > 0 ? round($total / $count, 2) : 0.0;

        $reportCard->forceFill([
            'total_marks' => round($total, 2),
            'average' => $average,
            'standing' => Standing::fromAverage($average),
        ])->save();

        return $reportCard->refresh();
    }

    /**
     * Dense-rank report cards within a class + term by average (DESC).
     */
    public function refreshRanksForClassTerm(int $schoolClassId, int $termId): void
    {
        $cards = ReportCard::query()
            ->where('school_class_id', $schoolClassId)
            ->where('term_id', $termId)
            ->orderByDesc('average')
            ->orderBy('id')
            ->get(['id', 'average']);

        $rank = 0;
        $position = 0;
        $previousAverage = null;

        DB::transaction(function () use ($cards, &$rank, &$position, &$previousAverage): void {
            foreach ($cards as $card) {
                $position++;
                $average = (string) $card->average;

                if ($previousAverage === null || $average !== $previousAverage) {
                    $rank = $position;
                    $previousAverage = $average;
                }

                ReportCard::query()->whereKey($card->id)->update(['rank' => $rank]);
            }
        });
    }

    public function refreshTotalsAndRanks(ReportCard $reportCard): ReportCard
    {
        $this->refreshTotals($reportCard);
        $this->refreshRanksForClassTerm(
            (int) $reportCard->school_class_id,
            (int) $reportCard->term_id,
        );

        return $reportCard->refresh();
    }
}
