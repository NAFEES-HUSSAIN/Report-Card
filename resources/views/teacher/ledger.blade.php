@extends('layouts.app')

@section('title', 'Class Ledger')

@section('content')
@php
    $students = $students ?? collect();
    $sort = $sort ?? 'rank';
    $direction = $direction ?? 'asc';
@endphp

<header class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="page-title">Class ledger</h1>
        <p class="page-subtitle">Live rankings with standing, averages, and attendance.</p>
    </div>
    @if (Route::has('teacher.form'))
        <a href="{{ route('teacher.form') }}" class="btn-primary">Input grades</a>
    @endif
</header>

<x-stats-cards
    :total-students="$totalStudents ?? 0"
    :class-average="$classAverage ?? 0"
    :pass-rate="$passRate ?? 0"
    :top-standing="$topStanding ?? '—'"
/>

<x-data-table title="Ranked class list" :paginator="$students" class="mt-8">
    <x-slot:toolbar>
        <form method="GET" action="{{ route('teacher.ledger') }}" class="flex w-full flex-col gap-3 sm:flex-row sm:flex-wrap" role="search">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <input type="hidden" name="direction" value="{{ $direction }}">

            <div class="min-w-[14rem] flex-1">
                <label for="q" class="input-label">Search</label>
                <input
                    type="search"
                    name="q"
                    id="q"
                    value="{{ $search ?? '' }}"
                    class="input-field"
                    placeholder="Name or index number"
                >
            </div>

            @if (($schoolClasses ?? collect())->isNotEmpty())
                <div class="min-w-[10rem]">
                    <label for="school_class_id" class="input-label">Class</label>
                    <select name="school_class_id" id="school_class_id" class="input-field">
                        @foreach ($schoolClasses as $class)
                            <option value="{{ $class->id }}" @selected((string) $selectedClassId === (string) $class->id)>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            @if (($terms ?? collect())->isNotEmpty())
                <div class="min-w-[10rem]">
                    <label for="term_id" class="input-label">Term</label>
                    <select name="term_id" id="term_id" class="input-field">
                        @foreach ($terms as $term)
                            <option value="{{ $term->id }}" @selected((string) $selectedTermId === (string) $term->id)>
                                {{ $term->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary">Apply</button>
                <a href="{{ route('teacher.ledger') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </x-slot:toolbar>

    <caption class="sr-only">Students ranked by standing and average</caption>
    <thead>
        <tr>
            <x-data-table.sort-th column="rank" label="Rank" :sort="$sort" :direction="$direction" />
            <x-data-table.sort-th column="index" label="Index no." :sort="$sort" :direction="$direction" />
            <x-data-table.sort-th column="name" label="Student" :sort="$sort" :direction="$direction" />
            <x-data-table.sort-th column="standing" label="Standing" :sort="$sort" :direction="$direction" />
            <x-data-table.sort-th column="average" label="Average" :sort="$sort" :direction="$direction" />
            <x-data-table.sort-th column="total" label="Total marks" :sort="$sort" :direction="$direction" />
            <th scope="col" class="table-th">Attendance</th>
            <th scope="col" class="table-th">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($students as $student)
            <tr class="table-row">
                <td class="table-td font-semibold tabular-nums">{{ data_get($student, 'rank', '—') }}</td>
                <td class="table-td font-mono text-xs">{{ data_get($student, 'index_number', '—') }}</td>
                <th scope="row" class="table-td font-medium">{{ data_get($student, 'name', '—') }}</th>
                <td class="table-td">
                    <span class="table-badge-accent">{{ data_get($student, 'standing', '—') }}</span>
                </td>
                <td class="table-td tabular-nums">{{ number_format((float) data_get($student, 'average', 0), 1) }}%</td>
                <td class="table-td tabular-nums">{{ number_format((float) data_get($student, 'total_marks', 0), 1) }}</td>
                <td class="table-td tabular-nums text-[var(--gs-muted)]">
                    {{ data_get($student, 'days_present', 0) }}/{{ data_get($student, 'total_days', 0) }}
                </td>
                <x-data-table.row-actions
                    :edit-url="auth()->user()?->hasPermission(\App\Support\SystemPermissions::TeacherGrades) && Route::has('teacher.form.edit') && data_get($student, 'id') ? route('teacher.form.edit', data_get($student, 'id')) : null"
                    :delete-url="auth()->user()?->hasPermission(\App\Support\SystemPermissions::TeacherGrades) && Route::has('teacher.report-cards.destroy') && data_get($student, 'report_card_id') ? route('teacher.report-cards.destroy', data_get($student, 'report_card_id')) : null"
                    delete-confirm="Delete this report card? Rankings will be recalculated."
                />
            </tr>
        @empty
            <tr>
                <td colspan="8" class="table-td py-10 text-center text-[var(--gs-muted)]">
                    No matching students found.
                    @if (Route::has('teacher.form'))
                        <a href="{{ route('teacher.form') }}" class="font-semibold text-[var(--gs-primary)] hover:underline">Add a report card</a>.
                    @endif
                </td>
            </tr>
        @endforelse
    </tbody>
</x-data-table>
@endsection
