<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'GradeSphere') — GradeSphere</title>

    <x-theme-boot />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[var(--gs-surface)] text-[var(--gs-ink)]">
    @hasSection('guest_header')
        @yield('guest_header')
    @else
        <header class="absolute inset-x-0 top-0 z-20 flex items-center justify-between px-4 py-4 sm:px-8">
            <a href="{{ Route::has('splash') ? route('splash') : url('/') }}" class="flex items-center gap-3">
                <x-brand-logo size="sm" />
                <span class="font-display text-lg font-semibold tracking-tight leading-none">GradeSphere</span>
            </a>
            <x-dark-mode-toggle />
        </header>
    @endif

    <main class="min-h-screen">
        @if (session('success'))
            <p class="flash-success mx-auto mt-20 max-w-lg px-4" role="status">{{ session('success') }}</p>
        @endif
        @if (session('error'))
            <p class="flash-error mx-auto mt-20 max-w-lg px-4" role="alert">{{ session('error') }}</p>
        @endif

        @yield('content')
    </main>
</body>
</html>
