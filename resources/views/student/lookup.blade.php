@extends('layouts.guest')

@section('title', 'Student lookup')

@section('content')
<section class="flex min-h-screen items-center justify-center px-4 py-24">
    <article class="card w-full max-w-md">
        <header class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl brand-gradient text-lg font-bold text-white shadow-lg shadow-violet-500/30">
                GS
            </div>
            <h1 class="page-title text-2xl">Find your report card</h1>
            <p class="page-subtitle">Enter your index number — no password required.</p>
        </header>

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

            <button type="submit" class="btn-primary w-full">Continue to dashboard</button>
        </form>

        <p class="mt-6 text-center text-sm text-[var(--gs-muted)]">
            Teacher?
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="font-semibold text-[var(--gs-primary)] hover:underline">Sign in</a>
            @endif
        </p>
    </article>
</section>
@endsection
