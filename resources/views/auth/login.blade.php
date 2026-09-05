@extends('layouts.guest')

@section('title', 'Teacher login')

@section('content')
<section class="grid min-h-screen lg:grid-cols-2">
    <aside class="guest-panel relative hidden items-end p-10 lg:flex lg:min-h-screen">
        <div class="relative z-10 max-w-md">
            <p class="font-display text-4xl font-bold leading-tight">Grade smarter.<br>Lead clearer.</p>
            <p class="mt-4 text-white/80">
                Sign in to manage marks, attendance, and live class standings in GradeSphere.
            </p>
        </div>
    </aside>

    <div class="flex items-center justify-center px-4 py-24 sm:px-8">
        <article class="card w-full max-w-md">
            <header class="mb-8">
                <h1 class="page-title text-2xl">Welcome back</h1>
                <p class="page-subtitle">Teacher login to your GradeSphere workspace</p>
            </header>

            <form method="POST" action="{{ Route::has('login') ? route('login') : url('/login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="input-label">Email</label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        class="input-field"
                        required
                        autofocus
                        autocomplete="username"
                    >
                    @error('email')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <label for="password" class="input-label !mb-0">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[var(--gs-primary)] hover:underline">Forgot password?</a>
                        @else
                            <span class="text-xs text-[var(--gs-muted)]">Forgot password?</span>
                        @endif
                    </div>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="input-field"
                        required
                        autocomplete="current-password"
                    >
                    @error('password')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-[var(--gs-muted)]">
                    <input type="checkbox" name="remember" value="1" class="rounded border-[var(--gs-line)] text-[var(--gs-primary)] focus:ring-[var(--gs-primary)]" {{ old('remember') ? 'checked' : '' }}>
                    Remember me
                </label>

                <button type="submit" class="btn-primary w-full">Sign in</button>
            </form>

            <p class="mt-6 text-center text-sm text-[var(--gs-muted)]">
                New teacher?
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="font-semibold text-[var(--gs-primary)] hover:underline">Create an account</a>
                @endif
            </p>
        </article>
    </div>
</section>
@endsection
