@props([
    'totalStudents' => 0,
    'classAverage' => 0,
    'passRate' => 0,
    'topStanding' => '—',
    'studentsTrend' => null,
    'averageTrend' => null,
    'passTrend' => null,
    'standingTrend' => null,
])

@php
    $cards = [
        [
            'label' => 'Total students',
            'value' => number_format($totalStudents ?? 0),
            'trend' => $studentsTrend,
            'tone' => 'from-violet-500 to-fuchsia-500',
            'soft' => 'bg-violet-50 text-violet-700 dark:bg-violet-950/50 dark:text-violet-200',
            'icon' => 'users',
        ],
        [
            'label' => 'Class average',
            'value' => number_format($classAverage ?? 0, 1).'%',
            'trend' => $averageTrend,
            'tone' => 'from-amber-400 to-orange-500',
            'soft' => 'bg-amber-50 text-amber-800 dark:bg-amber-950/40 dark:text-amber-200',
            'icon' => 'chart',
        ],
        [
            'label' => 'Pass rate',
            'value' => number_format($passRate ?? 0, 1).'%',
            'trend' => $passTrend,
            'tone' => 'from-emerald-400 to-teal-500',
            'soft' => 'bg-emerald-50 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-200',
            'icon' => 'check',
        ],
        [
            'label' => 'Top standing',
            'value' => $topStanding ?? '—',
            'trend' => $standingTrend,
            'tone' => 'from-sky-400 to-indigo-500',
            'soft' => 'bg-sky-50 text-sky-800 dark:bg-sky-950/40 dark:text-sky-200',
            'icon' => 'trophy',
        ],
    ];
@endphp

<section aria-label="Class performance summary" class="mb-8">
    <h2 class="sr-only">Class statistics</h2>
    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($cards as $card)
            <div class="card-interactive relative overflow-hidden !p-5">
                <div class="absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br {{ $card['tone'] }} opacity-20 blur-xl"></div>
                <div class="relative flex items-start justify-between gap-3">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--gs-muted)]">{{ $card['label'] }}</dt>
                        <dd class="mt-2 font-display text-3xl font-semibold tabular-nums text-[var(--gs-ink)]">{{ $card['value'] }}</dd>
                        @if (filled($card['trend']))
                            <p class="mt-2 inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-semibold {{ $card['soft'] }}">
                                <svg class="h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.577 4.878a.75.75 0 0 1 .916-.073l4.5 3.25a.75.75 0 0 1 0 1.24l-4.5 3.25a.75.75 0 0 1-1.17-.676V10.25H3.75a.75.75 0 0 1 0-1.5h7.673V5.551a.75.75 0 0 1 .154-.673Z" clip-rule="evenodd" />
                                </svg>
                                {{ $card['trend'] }}
                            </p>
                        @endif
                    </div>
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br {{ $card['tone'] }} text-white shadow-lg">
                        @if ($card['icon'] === 'users')
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                            </svg>
                        @elseif ($card['icon'] === 'chart')
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M15.5 2A1.5 1.5 0 0 0 14 3.5v13a1.5 1.5 0 0 0 3 0v-13A1.5 1.5 0 0 0 15.5 2ZM10 6A1.5 1.5 0 0 0 8.5 7.5v9a1.5 1.5 0 0 0 3 0v-9A1.5 1.5 0 0 0 10 6ZM4.5 10A1.5 1.5 0 0 0 3 11.5v5a1.5 1.5 0 0 0 3 0v-5A1.5 1.5 0 0 0 4.5 10Z" />
                            </svg>
                        @elseif ($card['icon'] === 'check')
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
                            </svg>
                        @else
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 1c-1.828 0-3.505.744-4.692 1.94C4.12 4.136 3.5 5.812 3.5 7.64c0 3.217 2.01 5.955 4.814 7.024L10 19l1.686-4.336C14.49 13.595 16.5 10.857 16.5 7.64c0-1.828-.62-3.504-1.808-4.7C13.505 1.744 11.828 1 10 1Zm0 4a2.75 2.75 0 1 0 0 5.5A2.75 2.75 0 0 0 10 5Z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </span>
                </div>
            </div>
        @endforeach
    </dl>
</section>
