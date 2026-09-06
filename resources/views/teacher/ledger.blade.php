@extends('layouts.app')

@section('title', 'Class Ledger')

@section('content')
@php
    $students = $students ?? collect();
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

<section class="card !p-0 overflow-hidden" aria-labelledby="ledger-heading">
    <div class="border-b border-[var(--gs-line)] px-6 py-4">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <h2 id="ledger-heading" class="font-display text-lg font-semibold">Ranked class list</h2>

            <form method="GET" action="{{ route('teacher.ledger') }}" class="flex w-full flex-col gap-3 sm:flex-row sm:flex-wrap lg:w-auto" role="search">
                @if ($selectedClassId)
                    <input type="hidden" name="school_class_id" value="{{ $selectedClassId }}">
                @endif
                @if ($selectedTermId)
                    <input type="hidden" name="term_id" value="{{ $selectedTermId }}">
                @endif

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
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full min-w-[48rem] border-collapse">
            <caption class="sr-only">Students ranked by standing and average</caption>
            <thead>
                <tr>
                    <th scope="col" class="table-th">Rank</th>
                    <th scope="col" class="table-th">Index no.</th>
                    <th scope="col" class="table-th">Student</th>
                    <th scope="col" class="table-th">Standing</th>
                    <th scope="col" class="table-th">Average</th>
                    <th scope="col" class="table-th">Total marks</th>
                    <th scope="col" class="table-th">Attendance</th>
                    <th scope="col" class="table-th"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr class="transition hover:bg-[var(--gs-primary-soft)]/40">
                        <td class="table-td font-semibold tabular-nums">{{ data_get($student, 'rank', '—') }}</td>
                        <td class="table-td font-mono text-xs">{{ data_get($student, 'index_number', '—') }}</td>
                        <th scope="row" class="table-td font-medium">{{ data_get($student, 'name', '—') }}</th>
                        <td class="table-td">
                            <span class="inline-flex rounded-full bg-[var(--gs-accent-soft)] px-2.5 py-1 text-xs font-semibold text-amber-800 dark:text-amber-200">
                                {{ data_get($student, 'standing', '—') }}
                            </span>
                        </td>
                        <td class="table-td tabular-nums">{{ number_format((float) data_get($student, 'average', 0), 1) }}%</td>
                        <td class="table-td tabular-nums">{{ number_format((float) data_get($student, 'total_marks', 0), 1) }}</td>
                        <td class="table-td tabular-nums text-[var(--gs-muted)]">
                            {{ data_get($student, 'days_present', 0) }}/{{ data_get($student, 'total_days', 0) }}
                        </td>
                        <td class="table-td">
                            @if (Route::has('teacher.form.edit') && data_get($student, 'id'))
                                <a href="{{ route('teacher.form.edit', data_get($student, 'id')) }}" class="text-sm font-semibold text-[var(--gs-primary)] hover:underline">Edit</a>
                            @endif
                        </td>
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
        </table>
    </div>

    @if (method_exists($students, 'links'))
        <div class="border-t border-[var(--gs-line)] px-6 py-4">
            {{ $students->links() }}
        </div>
    @endif
</section>
@endsection
