@props([
    'role' => null,
])

@php
    // Role-aware nav — swap for policies/middleware later
    $role = $role
        ?? (auth()->check() ? (auth()->user()->role ?? 'teacher') : (session()->has('student_id') ? 'student' : 'teacher'));

    $teacherLinks = [
        ['label' => 'Dashboard', 'route' => 'teacher.dashboard', 'match' => 'teacher.dashboard'],
        ['label' => 'Input Grades', 'route' => 'teacher.form', 'match' => 'teacher.form*'],
        ['label' => 'Class Ledger', 'route' => 'teacher.ledger', 'match' => 'teacher.ledger'],
    ];

    $studentLinks = [
        ['label' => 'Dashboard', 'route' => 'student.dashboard', 'match' => 'student.dashboard'],
        ['label' => 'Full Report', 'route' => 'student.report', 'match' => 'student.report'],
        ['label' => 'Lookup', 'route' => 'student.lookup', 'match' => 'student.lookup*'],
    ];

    $links = $role === 'student' ? $studentLinks : $teacherLinks;
@endphp

<aside {{ $attributes->merge(['class' => 'flex h-full flex-col border-r border-[var(--gs-line)] bg-[var(--gs-surface-elevated)]']) }}>
    <div class="flex items-center gap-3 border-b border-[var(--gs-line)] px-5 py-5">
        <span class="flex h-10 w-10 items-center justify-center rounded-2xl brand-gradient text-sm font-bold text-white shadow-lg shadow-violet-500/30">
            GS
        </span>
        <div>
            <p class="font-display text-lg font-semibold tracking-tight text-[var(--gs-ink)]">GradeSphere</p>
            <p class="text-xs text-[var(--gs-muted)]">{{ $role === 'student' ? 'Student portal' : 'Teacher portal' }}</p>
        </div>
    </div>

    <nav aria-label="Sidebar" class="flex-1 space-y-1 p-4">
        @foreach ($links as $link)
            @continue(! Route::has($link['route']))
            <a
                href="{{ route($link['route']) }}"
                class="nav-link {{ request()->routeIs($link['match']) ? 'nav-link-active' : '' }}"
            >
                @if ($link['label'] === 'Dashboard')
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                @elseif ($link['label'] === 'Input Grades')
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                    </svg>
                @elseif ($link['label'] === 'Class Ledger')
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                @elseif ($link['label'] === 'Full Report')
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                @else
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                @endif
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <div class="border-t border-[var(--gs-line)] p-4">
        @if (Route::has('splash'))
            <a href="{{ route('splash') }}" class="nav-link">
                <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                </svg>
                <span>Back to home</span>
            </a>
        @endif
    </div>
</aside>
