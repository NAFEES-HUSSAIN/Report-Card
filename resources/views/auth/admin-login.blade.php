@extends('layouts.guest')

@section('title', 'Admin login')

@section('guest_header')
    {{-- Login pages: no global guest chrome (logo bar / dark mode). --}}
    <span class="hidden" hidden aria-hidden="true"></span>
@endsection

@section('content')
<x-auth-gate
    portal="admin"
    headline="Principal control"
    lede="Manage teachers, permissions, and school-wide report cards from one secure portal."
    title="Admin sign in"
    subtitle="For principals and school administrators only"
>
    <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
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
            <input type="checkbox" name="remember" value="1" class="rounded border-[var(--gs-line)] text-[#14532d] focus:ring-[#14532d]">
            Remember me
        </label>
        <button type="submit" class="auth-gate-submit">Enter admin portal</button>
    </form>

    <p class="mt-8 text-center text-sm text-[var(--gs-muted)]">
        Not an admin?
        <a href="{{ route('teacher.login') }}" class="auth-gate-alt-link">Teacher sign in</a>
    </p>
</x-auth-gate>
@endsection
