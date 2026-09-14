@extends('layouts.guest')

@section('title', 'Welcome')

@section('content')
<section class="relative flex min-h-screen items-center justify-center overflow-hidden brand-gradient px-4 py-24">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute left-1/4 top-1/4 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-1/4 right-1/5 h-80 w-80 rounded-full bg-amber-300/20 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-3xl text-center text-white">
        <div class="splash-enter mx-auto mb-8 flex justify-center">
            <x-brand-logo size="xl" />
        </div>

        <h1 class="splash-enter font-display text-5xl font-bold tracking-tight sm:text-6xl">GradeSphere</h1>
        <p class="splash-enter-delay mt-4 text-lg text-white/85 sm:text-xl">Track. Grade. Grow.</p>
        <p class="splash-enter-delay mx-auto mt-3 max-w-xl text-sm text-white/70">
            Separate portals for principals, teachers, and students — with admin-controlled teacher permissions.
        </p>

        <div class="splash-enter-delay-2 mt-10 grid gap-4 sm:grid-cols-3">
            <a href="{{ route('admin.login') }}" class="rounded-2xl border border-white/25 bg-white/10 px-5 py-6 backdrop-blur transition hover:scale-[1.02] hover:bg-white/20">
                <p class="font-display text-xl font-semibold">Admin</p>
                <p class="mt-2 text-sm text-white/75">Principal portal</p>
            </a>
            <a href="{{ route('teacher.login') }}" class="rounded-2xl border border-white/25 bg-white/10 px-5 py-6 backdrop-blur transition hover:scale-[1.02] hover:bg-white/20">
                <p class="font-display text-xl font-semibold">Teacher</p>
                <p class="mt-2 text-sm text-white/75">Grades & ledger</p>
            </a>
            <a href="{{ route('student.lookup') }}" class="rounded-2xl border border-white/25 bg-white/10 px-5 py-6 backdrop-blur transition hover:scale-[1.02] hover:bg-white/20">
                <p class="font-display text-xl font-semibold">Student</p>
                <p class="mt-2 text-sm text-white/75">Index lookup</p>
            </a>
        </div>
    </div>
</section>
@endsection
