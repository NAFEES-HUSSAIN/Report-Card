<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\Standing;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ReportCard;
use App\Support\ReportCardPresenter;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(ReportCardPresenter $presenter): View
    {
        $this->authorize('viewAny', ReportCard::class);

        $year = AcademicYear::current();

        $reportCards = ReportCard::query()
            ->with(['student', 'term', 'schoolClass', 'subjectScores.subject'])
            ->when($year, function ($query) use ($year): void {
                $query->whereHas('term', fn ($termQuery) => $termQuery->where('academic_year_id', $year->id));
            })
            ->latest('updated_at')
            ->limit(8)
            ->get();

        $statsQuery = ReportCard::query()
            ->when($year, function ($query) use ($year): void {
                $query->whereHas('term', fn ($termQuery) => $termQuery->where('academic_year_id', $year->id));
            });

        $totalStudents = (clone $statsQuery)->distinct('student_id')->count('student_id');
        $classAverage = round((float) ((clone $statsQuery)->avg('average') ?? 0), 1);
        $passCount = (clone $statsQuery)->where('standing', '!=', Standing::Fail->value)->count();
        $totalCards = (clone $statsQuery)->count();
        $passRate = $totalCards > 0 ? round(($passCount / $totalCards) * 100, 1) : 0.0;
        $topStandingRaw = (clone $statsQuery)->orderByDesc('average')->value('standing');
        $topStanding = $topStandingRaw instanceof Standing
            ? $topStandingRaw->value
            : ($topStandingRaw ?: '—');

        $recentReports = $reportCards->map(fn (ReportCard $card) => $presenter->fromReportCard($card));

        return view('teacher.dashboard', [
            'shellRole' => 'teacher',
            'totalStudents' => $totalStudents,
            'classAverage' => $classAverage,
            'passRate' => $passRate,
            'topStanding' => $topStanding ?: '—',
            'studentsTrend' => null,
            'averageTrend' => null,
            'passTrend' => null,
            'standingTrend' => null,
            'recentReports' => $recentReports,
        ]);
    }
}
