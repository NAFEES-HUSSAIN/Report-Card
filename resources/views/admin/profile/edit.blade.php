@extends('layouts.app')

@section('title', 'Profile')

@section('content')
@php
    $editingSelf = $profileUser->is(auth()->user());
@endphp

<header class="mb-8">
    <h1 class="page-title">{{ $editingSelf ? 'Your profile' : 'Edit profile' }}</h1>
    <p class="page-subtitle">
        {{ $editingSelf ? 'Update your name, username, password, and photo.' : 'Manage account details for '.$profileUser->name.'.' }}
    </p>
</header>

<form
    method="POST"
    action="{{ $editingSelf ? route($shellRole === 'admin' ? 'admin.profile.update' : 'teacher.profile.update') : route('admin.users.profile.update', $profileUser) }}"
    enctype="multipart/form-data"
    class="space-y-6"
>
    @csrf
    @method('PUT')

    <section class="card flex flex-col gap-6 sm:flex-row sm:items-center">
        <div class="shrink-0">
            @if ($profileUser->avatarUrl())
                <img src="{{ $profileUser->avatarUrl() }}" alt="" class="h-24 w-24 rounded-3xl object-cover ring-2 ring-[var(--gs-line)]">
            @else
                <span class="flex h-24 w-24 items-center justify-center rounded-3xl brand-gradient text-2xl font-bold text-white">
                    {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                </span>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <label for="avatar" class="input-label">Profile photo</label>
            <input type="file" name="avatar" id="avatar" accept="image/*" class="input-field">
            @error('avatar')<p class="field-error">{{ $message }}</p>@enderror
            <p class="mt-2 text-xs text-[var(--gs-muted)]">JPG or PNG, max 2 MB.</p>
        </div>
    </section>

    <section class="card space-y-5">
        <h2 class="font-display text-lg font-semibold">Account details</h2>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="input-label">Full name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $profileUser->name) }}" class="input-field" required>
                @error('name')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="username" class="input-label">Username</label>
                <input type="text" name="username" id="username" value="{{ old('username', $profileUser->username) }}" class="input-field" required>
                @error('username')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="email" class="input-label">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $profileUser->email) }}" class="input-field" required>
                @error('email')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password" class="input-label">New password (optional)</label>
                <input type="password" name="password" id="password" class="input-field" autocomplete="new-password">
                @error('password')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="password_confirmation" class="input-label">Confirm password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="input-field" autocomplete="new-password">
            </div>

            @if ($canEditRole)
                <div>
                    <label for="role" class="input-label">Role</label>
                    <select name="role" id="role" class="input-field">
                        <option value="teacher" @selected(old('role', $profileUser->role->value) === 'teacher')>Teacher</option>
                        <option value="admin" @selected(old('role', $profileUser->role->value) === 'admin')>Admin</option>
                    </select>
                    @error('role')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm text-[var(--gs-ink)]">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-[var(--gs-line)] text-[var(--gs-primary)]" @checked(old('is_active', $profileUser->is_active))>
                        Account active
                    </label>
                </div>
            @endif
        </div>
    </section>

    <button type="submit" class="btn-primary">Save profile</button>
</form>
@endsection
