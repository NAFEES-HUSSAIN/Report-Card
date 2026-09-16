@extends('layouts.app')

@section('title', 'Admin dashboard')

@section('content')
<header class="mb-8">
    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[var(--gs-primary)]">Principal portal</p>
    <h1 class="page-title mt-2">Welcome, {{ auth()->user()->name }}</h1>
    <p class="page-subtitle">Control teacher access, review school activity, and open any portal.</p>
</header>

<section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <article class="card"><p class="text-xs font-semibold uppercase text-[var(--gs-muted)]">Teachers</p><p class="mt-2 font-display text-3xl font-semibold">{{ $stats['teachers'] }}</p></article>
    <article class="card"><p class="text-xs font-semibold uppercase text-[var(--gs-muted)]">Active teachers</p><p class="mt-2 font-display text-3xl font-semibold">{{ $stats['activeTeachers'] }}</p></article>
    <article class="card"><p class="text-xs font-semibold uppercase text-[var(--gs-muted)]">Students</p><p class="mt-2 font-display text-3xl font-semibold">{{ $stats['students'] }}</p></article>
    <article class="card"><p class="text-xs font-semibold uppercase text-[var(--gs-muted)]">Report cards</p><p class="mt-2 font-display text-3xl font-semibold">{{ $stats['reportCards'] }}</p></article>
</section>

<section class="mb-8 grid grid-cols-1 gap-4 lg:grid-cols-3">
    <a href="{{ route('teacher.dashboard') }}" class="card-interactive block overflow-hidden !p-0">
        <div class="brand-gradient px-5 py-6 text-white">
            <p class="text-xs font-semibold uppercase tracking-wider text-white/80">Open</p>
            <p class="mt-2 font-display text-2xl font-semibold">Teacher dashboard</p>
        </div>
    </a>
    <a href="{{ route('student.lookup') }}" class="card-interactive block overflow-hidden !p-0">
        <div class="bg-gradient-to-br from-amber-400 to-orange-500 px-5 py-6 text-slate-950">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-900/70">Open</p>
            <p class="mt-2 font-display text-2xl font-semibold">Student portal</p>
        </div>
    </a>
    <a href="{{ route('admin.teachers.index') }}" class="card-interactive block overflow-hidden !p-0">
        <div class="bg-gradient-to-br from-slate-800 to-violet-900 px-5 py-6 text-white">
            <p class="text-xs font-semibold uppercase tracking-wider text-white/80">Manage</p>
            <p class="mt-2 font-display text-2xl font-semibold">Teachers & permissions</p>
        </div>
    </a>
</section>

<section class="mt-2">
    <x-data-table title="Recent teachers" min-width="36rem">
        <thead>
            <tr>
                <th scope="col" class="table-th">Name</th>
                <th scope="col" class="table-th">Username</th>
                <th scope="col" class="table-th">Status</th>
                <th scope="col" class="table-th">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($recentTeachers as $teacher)
                <tr class="table-row">
                    <th scope="row" class="table-td font-medium">{{ $teacher->name }}</th>
                    <td class="table-td font-mono text-xs">{{ $teacher->username ?? '—' }}</td>
                    <td class="table-td">
                        <span class="{{ $teacher->is_active ? 'table-badge-success' : 'table-badge-danger' }}">
                            {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <x-data-table.row-actions
                        :edit-url="route('admin.teachers.edit', $teacher)"
                        :delete-url="route('admin.teachers.destroy', $teacher)"
                        delete-confirm="Remove this teacher account? They will no longer be able to sign in."
                    />
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="table-td py-10 text-center text-[var(--gs-muted)]">No teachers yet.</td>
                </tr>
            @endforelse
        </tbody>
    </x-data-table>
    <div class="mt-3 text-end">
        <a href="{{ route('admin.teachers.index') }}" class="text-sm font-semibold text-[var(--gs-primary)] hover:underline">View all teachers</a>
    </div>
</section>
@endsection
