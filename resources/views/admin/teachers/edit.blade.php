@extends('layouts.app')

@section('title', 'Edit teacher')

@section('content')
<header class="mb-8 flex flex-wrap items-end justify-between gap-4">
    <div>
        <h1 class="page-title">Edit teacher</h1>
        <p class="page-subtitle">Update profile, activation, and permissions for {{ $teacher->name }}.</p>
    </div>
    <a href="{{ route('admin.users.profile.edit', $teacher) }}" class="btn-secondary">Full profile</a>
</header>

<form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="space-y-6">
    @csrf
    @method('PUT')
    @include('admin.teachers._form', ['teacher' => $teacher, 'assignedKeys' => old('permissions', $assignedKeys)])
    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-primary">Save changes</button>
    </div>
</form>

<form method="POST" action="{{ route('admin.teachers.destroy', $teacher) }}" class="mt-8" onsubmit="return confirm('Remove this teacher account? They will no longer be able to sign in.')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-danger w-full sm:w-auto">Delete teacher</button>
</form>
@endsection
