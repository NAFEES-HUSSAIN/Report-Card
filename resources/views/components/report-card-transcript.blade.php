@props(['student' => null])

@php
    $subjects = collect(data_get($student, 'subjects', []));
@endphp

<section class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3 print:hidden">
        <div>
            <h1 class="page-title">Student report card</h1>
            <p class="page-subtitle">Official transcript — always prints in light mode</p>
        </div>
        <button type="button" onclick="window.print()" class="btn-primary">
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a24.301 24.301 0 0 1 5.28 0m5.28 0a24.28 24.28 0 0 1 .72.096m-.72-.096V17.25m0 0A2.25 2.25 0 0 1 15.75 19.5h-7.5A2.25 2.25 0 0 1 6 17.25m12.75 0v-3.378a3 3 0 0 0-.879-2.121l-2.372-2.372A3 3 0 0 0 13.378 9H10.5m0 0V4.5m0 4.5H6.75A2.25 2.25 0 0 0 4.5 11.25v6" />
            </svg>
            Print / Save as PDF
        </button>
    </div>

    <article class="print-force-light card ring-1 ring-[var(--gs-line)] print:shadow-none print:ring-0">
        <header class="mb-6 border-b border-[var(--gs-line)] pb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-[var(--gs-primary)]">GradeSphere · Official transcript</p>
            <h2 class="mt-2 font-display text-2xl font-semibold text-[var(--gs-ink)]">
                {{ data_get($student, 'name', '—') }}
            </h2>

            <dl class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-[var(--gs-muted)]">Index number</dt>
                    <dd class="mt-1 text-sm font-semibold">{{ data_get($student, 'index_number', '—') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-[var(--gs-muted)]">Class</dt>
                    <dd class="mt-1 text-sm font-semibold">{{ data_get($student, 'class_name', data_get($student, 'class', '—')) }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-[var(--gs-muted)]">Standing</dt>
                    <dd class="mt-1 text-sm font-semibold">{{ data_get($student, 'standing', '—') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-[var(--gs-muted)]">Rank</dt>
                    <dd class="mt-1 text-sm font-semibold">{{ data_get($student, 'rank', '—') }}</dd>
                </div>
            </dl>
        </header>

        <table class="w-full border-collapse">
            <caption class="sr-only">Subject marks and remarks</caption>
            <thead>
                <tr>
                    <th scope="col" class="table-th">Subject</th>
                    <th scope="col" class="table-th">Marks</th>
                    <th scope="col" class="table-th">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subjects as $subject)
                    <tr>
                        <th scope="row" class="table-td font-medium">
                            {{ data_get($subject, 'name', '—') }}
                        </th>
                        <td class="table-td tabular-nums">
                            {{ number_format((float) data_get($subject, 'marks', 0), 1) }}
                        </td>
                        <td class="table-td text-[var(--gs-muted)]">
                            {{ data_get($subject, 'remarks', '—') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="table-td py-8 text-center text-[var(--gs-muted)]">
                            No subject marks recorded yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <footer class="mt-6 grid grid-cols-1 gap-3 border-t border-[var(--gs-line)] pt-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-[var(--gs-primary-soft)] px-4 py-3">
                <p class="text-xs font-medium uppercase tracking-wide text-[var(--gs-muted)]">Average</p>
                <p class="mt-1 font-display text-lg font-semibold tabular-nums">
                    {{ number_format((float) data_get($student, 'average', 0), 1) }}%
                </p>
            </div>
            <div class="rounded-2xl bg-[var(--gs-accent-soft)] px-4 py-3">
                <p class="text-xs font-medium uppercase tracking-wide text-[var(--gs-muted)]">Total marks</p>
                <p class="mt-1 font-display text-lg font-semibold tabular-nums">
                    {{ number_format((float) data_get($student, 'total_marks', 0), 1) }}
                </p>
            </div>
            <div class="rounded-2xl bg-[var(--gs-surface)] px-4 py-3">
                <p class="text-xs font-medium uppercase tracking-wide text-[var(--gs-muted)]">Attendance</p>
                <p class="mt-1 font-display text-lg font-semibold tabular-nums">
                    {{ data_get($student, 'days_present', 0) }}/{{ data_get($student, 'total_days', 0) }}
                    <span class="text-sm font-normal text-[var(--gs-muted)]">present</span>
                </p>
            </div>
            <div class="rounded-2xl bg-[var(--gs-surface)] px-4 py-3">
                <p class="text-xs font-medium uppercase tracking-wide text-[var(--gs-muted)]">Days absent</p>
                <p class="mt-1 font-display text-lg font-semibold tabular-nums">
                    {{ data_get($student, 'days_absent', 0) }}
                </p>
            </div>
        </footer>
    </article>
</section>
