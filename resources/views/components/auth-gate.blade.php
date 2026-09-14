@props([
    'portal' => 'admin',
    'eyebrow' => 'GradeSphere',
    'headline',
    'lede',
    'title',
    'subtitle',
])

@php
    $portal = in_array($portal, ['admin', 'teacher', 'student'], true) ? $portal : 'admin';

    $portalLabel = match ($portal) {
        'teacher' => 'Teacher portal',
        'student' => 'Student portal',
        default => 'Admin portal',
    };
@endphp

<section {{ $attributes->merge(['class' => "auth-gate auth-gate--solo auth-gate--{$portal}"]) }}>
    <div class="auth-gate-panel">
        <div class="auth-gate-toolbar">
            <a href="{{ route('splash') }}" class="auth-gate-back">
                <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                Back to home
            </a>
        </div>

        <article class="auth-gate-card splash-enter">
            <div class="auth-gate-card-brand">
                <x-brand-logo size="md" />
                <p class="auth-gate-portal-chip auth-gate-portal-chip--light !mb-0">{{ $portalLabel }}</p>
            </div>

            <header class="mb-8 text-center">
                <h2 class="font-display text-2xl font-semibold tracking-tight text-[var(--gs-ink)]">{{ $title }}</h2>
                <p class="mt-1.5 text-sm text-[var(--gs-muted)]">{{ $subtitle }}</p>
            </header>

            {{ $slot }}
        </article>
    </div>
</section>
