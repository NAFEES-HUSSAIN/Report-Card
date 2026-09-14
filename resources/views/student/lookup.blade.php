@extends('layouts.guest')

@section('title', 'Student lookup')

@section('guest_header')
    {{-- Login pages: no global guest chrome (logo bar / dark mode). --}}
    <span class="hidden" hidden aria-hidden="true"></span>
@endsection

@section('content')
<x-auth-gate
    portal="student"
    headline="Student reports"
    lede="Look up your report card with your index number — no password required."
    title="Find your report card"
    subtitle="Enter the index number issued by your school"
>
    <form
        method="POST"
        action="{{ Route::has('student.lookup.submit') ? route('student.lookup.submit') : url()->current() }}"
        class="space-y-5"
    >
        @csrf

        <div>
            <label for="index_number" class="input-label">Index number</label>
            <input
                type="text"
                name="index_number"
                id="index_number"
                value="{{ old('index_number') }}"
                class="input-field text-center font-display text-lg tracking-wide"
                placeholder="e.g. STU-2026-0142"
                required
                autofocus
                autocomplete="off"
            >
            @error('index_number')
                <p class="field-error text-center" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="auth-gate-submit">Continue to dashboard</button>
    </form>

    <p class="mt-8 text-center text-sm text-[var(--gs-muted)]">
        Staff member?
        <a href="{{ route('teacher.login') }}" class="auth-gate-alt-link">Teacher sign in</a>
    </p>
</x-auth-gate>
@endsection
