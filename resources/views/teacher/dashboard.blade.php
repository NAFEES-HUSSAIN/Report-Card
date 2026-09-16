@extends('layouts.app')

@section('title', 'Teacher dashboard')

@section('content')
@php
    $recentReports = $recentReports ?? collect();
@endphp

<header class="mb-8">
    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[var(--gs-primary)]">Teacher workspace</p>
    <h1 class="page-title mt-2">
        Welcome back, {{ auth()->user()->name ?? ($teacherName ?? 'Teacher') }}
    </h1>
    <p class="page-subtitle">Here’s how your class is performing today.</p>
</header>

<x-stats-cards
    :total-students="$totalStudents ?? 0"
    :class-average="$classAverage ?? 0"
    :pass-rate="$passRate ?? 0"
    :top-standing="$topStanding ?? '—'"
    :students-trend="$studentsTrend ?? null"
    :average-trend="$averageTrend ?? null"
    :pass-trend="$passTrend ?? null"
    :standing-trend="$standingTrend ?? null"
/>

<div class="grid grid-cols-1 gap-6 xl:grid-cols-5">
    <section class="xl:col-span-3" aria-labelledby="recent-heading">
        <x-data-table title="Recent report cards" min-width="32rem">
            <thead>
                <tr>
                    <th scope="col" class="table-th">Student</th>
                    <th scope="col" class="table-th">Standing</th>
                    <th scope="col" class="table-th">Average</th>
                    <th scope="col" class="table-th">Updated</th>
                    <th scope="col" class="table-th">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentReports as $report)
                    <tr class="table-row">
                        <th scope="row" class="table-td font-medium">
                            {{ data_get($report, 'name', '—') }}
                            <span class="mt-0.5 block font-mono text-xs font-normal text-[var(--gs-muted)]">
                                {{ data_get($report, 'index_number', '') }}
                            </span>
                        </th>
                        <td class="table-td">
                            <span class="table-badge-primary">{{ data_get($report, 'standing', '—') }}</span>
                        </td>
                        <td class="table-td tabular-nums">
                            {{ number_format((float) data_get($report, 'average', 0), 1) }}%
                        </td>
                        <td class="table-td text-[var(--gs-muted)]">
                            {{ data_get($report, 'updated_at_human', data_get($report, 'updated_at', '—')) }}
                        </td>
                        <x-data-table.row-actions
                            :edit-url="auth()->user()?->hasPermission(\App\Support\SystemPermissions::TeacherGrades) && Route::has('teacher.form.edit') && data_get($report, 'id') ? route('teacher.form.edit', data_get($report, 'id')) : null"
                            :delete-url="auth()->user()?->hasPermission(\App\Support\SystemPermissions::TeacherGrades) && Route::has('teacher.report-cards.destroy') && data_get($report, 'report_card_id') ? route('teacher.report-cards.destroy', data_get($report, 'report_card_id')) : null"
                            delete-confirm="Delete this report card? Rankings will be recalculated. If this is the student's only record, they will be removed from student lookup."
                        />
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="table-td py-10 text-center text-[var(--gs-muted)]">
                            No recent report cards yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-data-table>
        @if (Route::has('teacher.ledger'))
            <div class="mt-3 text-end">
                <a href="{{ route('teacher.ledger') }}" class="text-sm font-semibold text-[var(--gs-primary)] hover:underline">View ledger</a>
            </div>
        @endif
    </section>

    <section class="space-y-4 xl:col-span-2" aria-labelledby="actions-heading">
        <h2 id="actions-heading" class="font-display text-xl font-semibold">Quick actions</h2>

        @if (Route::has('teacher.form'))
            <a href="{{ route('teacher.form') }}" class="card-interactive group block overflow-hidden !p-0">
                <div class="brand-gradient px-5 py-6 text-white">
                    <p class="text-xs font-semibold uppercase tracking-wider text-white/80">Create</p>
                    <p class="mt-2 font-display text-2xl font-semibold">Add grades</p>
                    <p class="mt-1 text-sm text-white/80">Enter marks, remarks, and attendance.</p>
                </div>
            </a>
        @endif

        @if (Route::has('teacher.ledger'))
            <a href="{{ route('teacher.ledger') }}" class="card-interactive group block overflow-hidden !p-0">
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 px-5 py-6 text-slate-950">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-900/70">Review</p>
                    <p class="mt-2 font-display text-2xl font-semibold">View ledger</p>
                    <p class="mt-1 text-sm text-slate-900/75">Live rankings and class standings.</p>
                </div>
            </a>
        @endif
    </section>
</div>
@endsection
