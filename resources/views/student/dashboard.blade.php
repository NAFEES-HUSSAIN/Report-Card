@extends('layouts.app')

@section('title', 'Student dashboard')

@section('content')
@php
    $student = $student ?? null;
    $showTranscript = (bool) ($showTranscript ?? request()->boolean('transcript'));
    $studentName = data_get($student, 'name', session('student_name', 'Student'));
    $indexNumber = data_get($student, 'index_number', '—');
    $className = data_get($student, 'class_name', data_get($student, 'class', '—'));
    $term = data_get($student, 'term') ?: '—';
    $standing = data_get($student, 'standing', '—');
    $rank = data_get($student, 'rank');
    $average = (float) data_get($student, 'average', 0);
    $daysPresent = (int) data_get($student, 'days_present', 0);
    $daysAbsent = (int) data_get($student, 'days_absent', 0);
    $totalDays = (int) data_get($student, 'total_days', 0);
    $hasReportCard = filled(data_get($student, 'report_card_id'));
    $attendancePct = $totalDays > 0
        ? round(($daysPresent / $totalDays) * 100)
        : null;
@endphp

<div class="student-dash">
    <section class="student-dash-hero" aria-labelledby="student-dash-greeting">
        <div class="student-dash-hero-copy">
            <p class="student-dash-kicker">Student portal</p>
            <h1 id="student-dash-greeting" class="student-dash-title">
                Hello, {{ $studentName }}
            </h1>
            <p class="student-dash-lede">
                Your term overview for K/Almanar Central College. Open the full report when you need to print.
            </p>

            <dl class="student-dash-identity">
                <div>
                    <dt>Index</dt>
                    <dd>{{ $indexNumber }}</dd>
                </div>
                <div>
                    <dt>Class</dt>
                    <dd>{{ $className }}</dd>
                </div>
                <div>
                    <dt>Term</dt>
                    <dd>{{ $term }}</dd>
                </div>
            </dl>
        </div>

        <div class="student-dash-crest" aria-hidden="true">
            <x-brand-logo size="lg" />
        </div>
    </section>

    <section class="student-dash-metrics" aria-label="Term summary">
        <article class="student-dash-metric">
            <p class="student-dash-metric-label">Average</p>
            <p class="student-dash-metric-value tabular-nums">
                {{ $hasReportCard ? number_format($average, 1).'%' : '—' }}
            </p>
        </article>
        <article class="student-dash-metric">
            <p class="student-dash-metric-label">Standing</p>
            <p class="student-dash-metric-value">{{ $hasReportCard ? $standing : '—' }}</p>
        </article>
        <article class="student-dash-metric">
            <p class="student-dash-metric-label">Rank</p>
            <p class="student-dash-metric-value tabular-nums">
                {{ $hasReportCard && filled($rank) ? $rank : '—' }}
            </p>
        </article>
        <article class="student-dash-metric student-dash-metric-accent">
            <p class="student-dash-metric-label">Attendance</p>
            <p class="student-dash-metric-value tabular-nums">
                @if ($attendancePct !== null)
                    {{ $attendancePct }}%
                @else
                    —
                @endif
            </p>
            @if ($totalDays > 0)
                <p class="student-dash-metric-hint">{{ $daysPresent }} present · {{ $daysAbsent }} absent</p>
            @endif
        </article>
    </section>

    <section class="student-dash-panel" aria-labelledby="student-dash-actions-heading">
        <div class="student-dash-panel-copy">
            <h2 id="student-dash-actions-heading" class="student-dash-panel-title">
                {{ $hasReportCard ? 'Report card ready' : 'No report card yet' }}
            </h2>
            <p class="student-dash-panel-text">
                @if ($hasReportCard)
                    View the official transcript with subject marks, remarks, and attendance — or reveal it on this page.
                @else
                    Your grades for this term have not been published yet. Check back after your teacher submits marks.
                @endif
            </p>
        </div>

        <div class="student-dash-actions">
            @if ($hasReportCard && Route::has('student.report'))
                <a href="{{ route('student.report') }}" class="auth-gate-submit !w-auto px-6">
                    View full report card
                </a>
            @endif

            @if ($hasReportCard)
                @if ($showTranscript)
                    <a href="{{ route('student.dashboard') }}" class="btn-secondary">Hide transcript</a>
                @else
                    <a href="{{ route('student.dashboard', ['transcript' => 1]) }}" class="btn-secondary">
                        Reveal transcript here
                    </a>
                @endif
            @else
                <a href="{{ route('student.lookup') }}" class="btn-secondary">Look up another index</a>
            @endif
        </div>
    </section>

    @if ($showTranscript && $hasReportCard)
        <div class="student-dash-transcript mt-8">
            <x-report-card-transcript :student="$student" />
        </div>
    @endif
</div>
@endsection
