@extends('layouts.guest')

@section('title', 'Teacher login')

@section('content')
<section class="grid min-h-screen lg:grid-cols-2">
    <aside class="guest-panel relative hidden items-end p-10 lg:flex lg:min-h-screen">
        <div class="relative z-10 max-w-md">
            <p class="font-display text-4xl font-bold leading-tight">Teacher workspace.</p>
            <p class="mt-4 text-white/80">
                Sign in to enter grades and view class standings — once your principal has activated your account.
            </p>
        </div>
    </aside>

    <div class="flex items-center justify-center px-4 py-24 sm:px-8">
        <article class="card w-full max-w-md">
            <header class="mb-8">
                <h1 class="page-title text-2xl">Teacher sign in</h1>
                <p class="page-subtitle">Accounts are created and permissioned by admin</p>
            </header>

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
                    <input type="checkbox" name="remember" value="1" class="rounded border-[var(--gs-line)] text-[var(--gs-primary)]">
                    Remember me
                </label>
                <button type="submit" class="btn-primary w-full">Enter teacher portal</button>
            </form>
        </article>
    </div>
</section>
@endsection
