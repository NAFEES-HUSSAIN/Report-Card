@props([
    'role' => null,
])

@php
    use App\Support\SystemPermissions;

    $role = $role
        ?? (auth()->check() ? (auth()->user()->role?->value ?? 'teacher') : (session()->has('student_id') ? 'student' : 'teacher'));

    $user = auth()->user();

    $adminLinks = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'permission' => SystemPermissions::AdminOverview],
        ['label' => 'Teachers', 'route' => 'admin.teachers.index', 'match' => 'admin.teachers.*', 'permission' => SystemPermissions::AdminTeachers],
        ['label' => 'School logo', 'route' => 'admin.branding.edit', 'match' => 'admin.branding.*', 'permission' => SystemPermissions::AdminProfiles],
        ['label' => 'My profile', 'route' => 'admin.profile.edit', 'match' => 'admin.profile.*', 'permission' => null],
        ['label' => 'Teacher portal', 'route' => 'teacher.dashboard', 'match' => 'teacher.*', 'permission' => SystemPermissions::TeacherDashboard],
        ['label' => 'Student portal', 'route' => 'student.lookup', 'match' => 'student.*', 'permission' => null],
    ];

    $teacherLinks = [
        ['label' => 'Dashboard', 'route' => 'teacher.dashboard', 'match' => 'teacher.dashboard', 'permission' => SystemPermissions::TeacherDashboard],
        ['label' => 'Input Grades', 'route' => 'teacher.form', 'match' => 'teacher.form*', 'permission' => SystemPermissions::TeacherGrades],
        ['label' => 'Class Ledger', 'route' => 'teacher.ledger', 'match' => 'teacher.ledger', 'permission' => SystemPermissions::TeacherLedger],
        ['label' => 'My profile', 'route' => 'teacher.profile.edit', 'match' => 'teacher.profile.*', 'permission' => null],
    ];

    if ($user?->isAdmin() && $role === 'teacher') {
        array_unshift($teacherLinks, [
            'label' => 'Admin portal',
            'route' => 'admin.dashboard',
            'match' => 'admin.*',
            'permission' => SystemPermissions::AdminOverview,
        ]);
    }

    $studentLinks = [
        ['label' => 'Dashboard', 'route' => 'student.dashboard', 'match' => 'student.dashboard', 'permission' => null],
        ['label' => 'Full Report', 'route' => 'student.report', 'match' => 'student.report', 'permission' => null],
        ['label' => 'Lookup', 'route' => 'student.lookup', 'match' => 'student.lookup*', 'permission' => null],
    ];

    $links = match ($role) {
        'admin' => $adminLinks,
        'student' => $studentLinks,
        default => $teacherLinks,
    };

    $portalLabel = match ($role) {
        'admin' => 'Admin portal',
        'student' => 'Student portal',
        default => 'Teacher portal',
    };
@endphp

<aside {{ $attributes->merge(['class' => 'flex h-full flex-col border-r border-[var(--gs-line)] bg-[var(--gs-surface-elevated)]']) }}>
    <div class="flex items-center gap-3 border-b border-[var(--gs-line)] px-5 py-5">
        <x-brand-logo />
        <div>
            <p class="font-display text-lg font-semibold tracking-tight text-[var(--gs-ink)]">GradeSphere</p>
            <p class="text-xs text-[var(--gs-muted)]">{{ $portalLabel }}</p>
        </div>
    </div>

    <nav aria-label="Sidebar" class="flex-1 space-y-1 p-4">
        @foreach ($links as $link)
            @continue(! Route::has($link['route']))
            @if ($link['permission'] && $user && ! $user->hasPermission($link['permission']))
                @continue
            @endif
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
                @elseif ($link['label'] === 'Teachers')
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                @elseif ($link['label'] === 'My profile')
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                @elseif ($link['label'] === 'Teacher portal')
                    <svg class="h-5 w-5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                @elseif ($link['label'] === 'Student portal' || $link['label'] === 'Full Report')
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
