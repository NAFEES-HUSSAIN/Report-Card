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
    @php
        $shellRole = $shellRole
            ?? (auth()->check()
                ? (auth()->user()->role?->value ?? 'teacher')
                : (session()->has('student_id') ? 'student' : 'teacher'));
        $userName = auth()->user()->name ?? session('student_name') ?? 'Guest';
        $userInitial = strtoupper(substr($userName, 0, 1));
    @endphp

    <div class="min-h-screen lg:flex" data-app-shell>
        {{-- Desktop sidebar --}}
        <div class="hidden w-72 shrink-0 lg:sticky lg:top-0 lg:block lg:h-screen lg:overflow-y-auto">
            <x-nav-sidebar :role="$shellRole" class="h-full min-h-screen" />
        </div>

        {{-- Mobile drawer --}}
        <div id="mobile-sidebar" class="fixed inset-0 z-50 hidden lg:hidden" role="dialog" aria-modal="true" aria-label="Navigation">
            <button type="button" class="absolute inset-0 bg-black/50" data-sidebar-close aria-label="Close menu"></button>
            <div class="relative h-full w-72 max-w-[85vw] shadow-2xl">
                <x-nav-sidebar :role="$shellRole" class="h-full" />
            </div>
        </div>

        <div class="flex min-h-screen min-w-0 flex-1 flex-col">
            <header class="print:hidden sticky top-0 z-30 flex items-center justify-between gap-4 border-b border-[var(--gs-line)] bg-[var(--gs-surface-elevated)]/90 px-4 py-3 backdrop-blur-md sm:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" class="btn-ghost lg:hidden" data-sidebar-open aria-label="Open menu">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <p class="font-display text-base font-semibold lg:hidden">GradeSphere</p>
                </div>

                <div class="flex items-center gap-3">
                    <x-dark-mode-toggle />

                    <div class="hidden items-center gap-3 rounded-2xl border border-[var(--gs-line)] bg-[var(--gs-surface)] px-3 py-1.5 sm:flex">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl brand-gradient text-xs font-bold text-white">
                            {{ $userInitial }}
                        </span>
                        <span class="text-sm font-semibold text-[var(--gs-ink)]">{{ $userName }}</span>
                    </div>

                    @auth
                        @if (Route::has('logout'))
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-secondary !px-3 !py-2 text-xs sm:!px-4 sm:!py-2.5 sm:text-sm">Log out</button>
                            </form>
                        @endif
                    @else
                        @if (session()->has('student_id') && Route::has('student.logout'))
                            <form method="POST" action="{{ route('student.logout') }}">
                                @csrf
                                <button type="submit" class="btn-secondary !px-3 !py-2 text-xs sm:!px-4 sm:!py-2.5 sm:text-sm">Sign out</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </header>

            <main class="flex-1 px-4 py-8 sm:px-6 lg:px-8 print:px-0 print:py-0">
                @if (session('success'))
                    <p class="flash-success print:hidden" role="status">{{ session('success') }}</p>
                @endif

                @if (session('error'))
                    <p class="flash-error print:hidden" role="alert">{{ session('error') }}</p>
                @endif

                @if ($errors->any())
                    <div class="flash-error print:hidden" role="alert">
                        <p class="font-medium">Please fix the following:</p>
                        <ul class="mt-2 list-disc ps-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="print:hidden border-t border-[var(--gs-line)] px-4 py-5 text-sm text-[var(--gs-muted)] sm:px-6">
                <p>&copy; {{ date('Y') }} GradeSphere. Track. Grade. Grow.</p>
            </footer>
        </div>
    </div>

    <script>
        (function () {
            const sidebar = document.getElementById('mobile-sidebar');
            if (!sidebar) return;
            document.querySelector('[data-sidebar-open]')?.addEventListener('click', () => {
                sidebar.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
            document.querySelector('[data-sidebar-close]')?.addEventListener('click', () => {
                sidebar.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            });
        })();
    </script>
</body>
</html>
