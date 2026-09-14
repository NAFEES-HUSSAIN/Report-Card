@extends('layouts.guest')

@section('title', 'Teacher login')

@section('guest_header')
    {{-- Login pages: no global guest chrome (logo bar / dark mode). --}}
    <span class="hidden" hidden aria-hidden="true"></span>
@endsection

@section('content')
<x-auth-gate
    portal="teacher"
    headline="Teacher workspace"
    lede="Enter grades and review class standings once your principal has activated your account."
    title="Teacher sign in"
    subtitle="Accounts are created and permissioned by admin"
>
    <form method="POST" action="{{ route('teacher.login') }}" class="space-y-5">
        @csrf
        <div>
            <label for="login" class="input-label">Email or username</label>
            <input type="text" name="login" id="login" value="{{ old('login') }}" class="input-field" required autofocus autocomplete="username">
            @error('login')<p class="field-error" role="alert">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="input-label">Password</label>
            <input type="password" name="password" id="password" class="input-field" required autocomplete="current-password">
            @error('password')<p class="field-error" role="alert">{{ $message }}</p>@enderror
        </div>
        <label class="flex items-center gap-2 text-sm text-[var(--gs-muted)]">
            <input type="checkbox" name="remember" value="1" class="rounded border-[var(--gs-line)] text-[#0f766e] focus:ring-[#0f766e]">
            Remember me
        </label>
        <button type="submit" class="auth-gate-submit">Enter teacher portal</button>
    </form>

    <p class="mt-8 text-center text-sm text-[var(--gs-muted)]">
        Looking for reports?
        <a href="{{ route('student.lookup') }}" class="auth-gate-alt-link">Student lookup</a>
    </p>
</x-auth-gate>
@endsection
