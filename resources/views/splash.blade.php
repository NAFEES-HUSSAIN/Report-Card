@extends('layouts.guest')

@section('title', 'Welcome')

@section('content')
<section class="relative flex min-h-screen items-center justify-center overflow-hidden brand-gradient px-4 py-24">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute left-1/4 top-1/4 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/5 h-80 w-80 rounded-full bg-amber-300/20 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-2xl text-center text-white">
        <div class="splash-enter mx-auto mb-8 flex h-20 w-20 items-center justify-center rounded-3xl bg-white/15 text-2xl font-bold backdrop-blur-md ring-1 ring-white/30">
            GS
        </div>

        <h1 class="splash-enter font-display text-5xl font-bold tracking-tight sm:text-6xl">
            GradeSphere
        </h1>
        <p class="splash-enter-delay mt-4 text-lg text-white/85 sm:text-xl">
            Track. Grade. Grow.
        </p>
        <p class="splash-enter-delay mx-auto mt-3 max-w-md text-sm text-white/70">
            A modern report card portal for teachers who want clarity — and students who want their standing at a glance.
        </p>

        <div class="splash-enter-delay-2 mt-10 flex flex-wrap items-center justify-center gap-4">
            <a href="{{ Route::has('login') ? route('login') : url('/login') }}" class="btn-accent !text-slate-950">
                Get Started
            </a>
            @if (Route::has('student.lookup'))
                <a href="{{ route('student.lookup') }}" class="rounded-2xl border border-white/30 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:scale-[1.02] hover:bg-white/20">
                    Student lookup
                </a>
            @endif
        </div>
    </div>
</section>
@endsection
