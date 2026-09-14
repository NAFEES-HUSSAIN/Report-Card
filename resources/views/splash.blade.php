@extends('layouts.guest')

@section('title', 'Welcome')

@section('guest_header')
<header class="splash-chrome absolute inset-x-0 top-0 z-30 flex items-center justify-between px-4 py-5 sm:px-8">
    <p class="font-display text-sm font-semibold tracking-[0.18em] text-white/70 uppercase">GradeSphere</p>
</header>
@endsection

@section('content')
<section class="splash-stage relative flex min-h-screen items-center justify-center overflow-hidden px-4 pb-16 pt-24 sm:px-6">
    <div class="splash-atmosphere pointer-events-none absolute inset-0" aria-hidden="true">
        <div class="splash-mesh"></div>
        <div class="splash-orb splash-orb-a"></div>
        <div class="splash-orb splash-orb-b"></div>
        <div class="splash-grid"></div>
        <div class="splash-vignette"></div>
    </div>

    <div class="relative z-10 mx-auto flex w-full max-w-3xl flex-col items-center text-center">
        <div class="splash-enter">
            <div class="splash-mark">
                <x-brand-logo size="splash" />
            </div>
        </div>

        <h1 class="splash-enter font-display mt-8 text-[1.65rem] font-semibold leading-tight tracking-tight text-white sm:text-4xl md:text-5xl">
            K/Almanar Central College
        </h1>

        <p class="splash-enter-delay mt-3 font-display text-base font-medium tracking-[0.12em] text-[var(--splash-gold)] uppercase sm:text-lg">
            Track. Grade. Grow.
        </p>

        <p class="splash-enter-delay mx-auto mt-4 max-w-md text-sm leading-relaxed text-white/70 sm:text-base">
            Choose your portal to continue.
        </p>

        <nav class="splash-enter-delay-2 mt-10 grid w-full max-w-2xl gap-3 sm:grid-cols-3 sm:gap-4" aria-label="Portals">
            <a href="{{ route('admin.login') }}" class="splash-portal">
                <span class="splash-portal-label">Admin</span>
                <span class="splash-portal-hint">Principal</span>
            </a>
            <a href="{{ route('teacher.login') }}" class="splash-portal">
                <span class="splash-portal-label">Teacher</span>
                <span class="splash-portal-hint">Grades &amp; ledger</span>
            </a>
            <a href="{{ route('student.lookup') }}" class="splash-portal">
                <span class="splash-portal-label">Student</span>
                <span class="splash-portal-hint">Index lookup</span>
            </a>
        </nav>
    </div>
</section>
@endsection
