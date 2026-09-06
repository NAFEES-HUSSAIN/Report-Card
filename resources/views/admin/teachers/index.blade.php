@extends('layouts.app')

@section('title', 'Teachers')

@section('content')
<header class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="page-title">Teachers</h1>
        <p class="page-subtitle">Create accounts, activate access, and assign feature permissions.</p>
    </div>
    <a href="{{ route('admin.teachers.create') }}" class="btn-primary">Add teacher</a>
</header>

<x-data-table title="Teacher directory" :paginator="$teachers">
    <x-slot:toolbar>
        <form method="GET" action="{{ route('admin.teachers.index') }}" class="flex w-full flex-col gap-3 sm:flex-row sm:flex-wrap" role="search">
            @if ($sort)
                <input type="hidden" name="sort" value="{{ $sort }}">
            @endif
            @if ($direction)
                <input type="hidden" name="direction" value="{{ $direction }}">
            @endif

            <div class="min-w-[14rem] flex-1">
                <label for="q" class="input-label">Search</label>
                <input type="search" name="q" id="q" value="{{ $search }}" class="input-field" placeholder="Name, username, or email">
            </div>

            <div class="min-w-[10rem]">
                <label for="status" class="input-label">Status</label>
                <select name="status" id="status" class="input-field">
                    <option value="">All</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn-primary">Apply</button>
                <a href="{{ route('admin.teachers.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </x-slot:toolbar>

    <thead>
        <tr>
            <x-data-table.sort-th column="name" label="Teacher" :sort="$sort" :direction="$direction" />
            <x-data-table.sort-th column="username" label="Username" :sort="$sort" :direction="$direction" />
            <x-data-table.sort-th column="email" label="Email" :sort="$sort" :direction="$direction" />
            <x-data-table.sort-th column="status" label="Status" :sort="$sort" :direction="$direction" />
            <th scope="col" class="table-th">Permissions</th>
            <th scope="col" class="table-th">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($teachers as $teacher)
            <tr class="table-row">
                <th scope="row" class="table-td font-medium">{{ $teacher->name }}</th>
                <td class="table-td font-mono text-xs">{{ $teacher->username ?? '—' }}</td>
                <td class="table-td">{{ $teacher->email }}</td>
                <td class="table-td">
                    <span class="{{ $teacher->is_active ? 'table-badge-success' : 'table-badge-danger' }}">
                        {{ $teacher->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="table-td text-sm text-[var(--gs-muted)]">{{ $teacher->permissions->count() }} granted</td>
                <x-data-table.row-actions
                    :edit-url="route('admin.teachers.edit', $teacher)"
                    :delete-url="route('admin.teachers.destroy', $teacher)"
                    delete-confirm="Remove this teacher account?"
                />
            </tr>
        @empty
            <tr>
                <td colspan="6" class="table-td py-10 text-center text-[var(--gs-muted)]">
                    No teachers match your filters.
                    <a href="{{ route('admin.teachers.create') }}" class="font-semibold text-[var(--gs-primary)] hover:underline">Add a teacher</a>.
                </td>
            </tr>
        @endforelse
    </tbody>
</x-data-table>
@endsection
