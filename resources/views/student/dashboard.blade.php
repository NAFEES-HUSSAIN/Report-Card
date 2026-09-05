@extends('layouts.app')

@section('title', 'Student dashboard')

@section('content')
@php
    $student = $student ?? null;
    $showTranscript = (bool) ($showTranscript ?? request()->boolean('transcript'));
@endphp

<header class="mb-8">
    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[var(--gs-primary)]">Student portal</p>
    <h1 class="page-title mt-2">
        Hello, {{ data_get($student, 'name', session('student_name', 'Student')) }}
    </h1>
    <p class="page-subtitle">Your term summary is ready. Open the full transcript when you need to print.</p>
</header>

<section class="card relative overflow-hidden" aria-labelledby="summary-heading">
    <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-gradient-to-br from-[var(--gs-primary)] to-[var(--gs-accent)] opacity-20 blur-2xl"></div>
    <div class="relative">
        <h2 id="summary-heading" class="font-display text-xl font-semibold">Term summary</h2>
        <dl class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-2xl bg-[var(--gs-primary-soft)] p-4">
                <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--gs-muted)]">Average</dt>
                <dd class="mt-2 font-display text-3xl font-semibold tabular-nums">
                    {{ number_format((float) data_get($student, 'average', 0), 1) }}%
                </dd>
            </div>
            <div class="rounded-2xl bg-[var(--gs-accent-soft)] p-4">
                <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--gs-muted)]">Standing</dt>
                <dd class="mt-2 font-display text-3xl font-semibold">
                    {{ data_get($student, 'standing', '—') }}
                </dd>
            </div>
            <div class="rounded-2xl bg-[var(--gs-surface)] p-4 ring-1 ring-[var(--gs-line)]">
                <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--gs-muted)]">Term</dt>
                <dd class="mt-2 font-display text-2xl font-semibold">
                    {{ data_get($student, 'term', '—') }}
                </dd>
            </div>
        </dl>

        <div class="mt-6 flex flex-wrap gap-3">
            @if (Route::has('student.report'))
                <a href="{{ route('student.report') }}" class="btn-primary">View Full Report Card</a>
            @endif
            <a href="{{ route('student.dashboard', ['transcript' => 1]) }}" class="btn-secondary">
                Reveal transcript here
            </a>
        </div>
    </div>
</section>

@if ($showTranscript)
    <div class="mt-8">
        <x-report-card-transcript :student="$student" />
    </div>
@endif
@endsection
