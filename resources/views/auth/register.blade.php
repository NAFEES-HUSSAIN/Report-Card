@extends('layouts.guest')

@section('title', 'Create teacher account')

@section('content')
<section class="grid min-h-screen lg:grid-cols-2">
    <aside class="guest-panel relative hidden items-end p-10 lg:flex lg:min-h-screen">
        <div class="relative z-10 max-w-md">
            <p class="font-display text-4xl font-bold leading-tight">Join GradeSphere</p>
            <p class="mt-4 text-white/80">
                Set up your teacher account and start publishing clear, printable report cards in minutes.
            </p>
        </div>
    </aside>

    <div class="flex items-center justify-center px-4 py-24 sm:px-8">
        <article class="card w-full max-w-md">
            <header class="mb-8">
                <h1 class="page-title text-2xl">Create account</h1>
                <p class="page-subtitle">Register as a teacher on GradeSphere</p>
            </header>

            <form method="POST" action="{{ Route::has('register') ? route('register') : url('/register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="input-label">Full name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="input-field" required autofocus autocomplete="name">
                    @error('name')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="input-label">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="input-field" required autocomplete="username">
                    @error('email')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="input-label">Password</label>
                    <input type="password" name="password" id="password" class="input-field" required autocomplete="new-password">
                    @error('password')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="input-label">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-field" required autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full">Create account</button>
            </form>

            <p class="mt-6 text-center text-sm text-[var(--gs-muted)]">
                Already registered?
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="font-semibold text-[var(--gs-primary)] hover:underline">Sign in</a>
                @endif
            </p>
        </article>
    </div>
</section>
@endsection
