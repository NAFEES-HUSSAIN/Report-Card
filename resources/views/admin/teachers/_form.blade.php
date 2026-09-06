<section class="card space-y-5">
    <h2 class="font-display text-lg font-semibold">Account</h2>
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="name" class="input-label">Full name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $teacher?->name) }}" class="input-field" required>
            @error('name')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="username" class="input-label">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username', $teacher?->username) }}" class="input-field" required>
            @error('username')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="input-label">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $teacher?->email) }}" class="input-field" required>
            @error('email')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-end">
            <label class="flex items-center gap-2 text-sm text-[var(--gs-ink)]">
                <input type="checkbox" name="is_active" value="1" class="rounded border-[var(--gs-line)] text-[var(--gs-primary)]" @checked(old('is_active', $teacher?->is_active ?? false))>
                Account active (can sign in)
            </label>
        </div>
        <div>
            <label for="password" class="input-label">Password {{ $teacher ? '(optional)' : '' }}</label>
            <input type="password" name="password" id="password" class="input-field" @required(! $teacher) autocomplete="new-password">
            @error('password')<p class="field-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="input-label">Confirm password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="input-field" autocomplete="new-password">
        </div>
    </div>
</section>

@if (auth()->user()?->hasPermission(\App\Support\SystemPermissions::AdminPermissions))
<section class="card space-y-4">
    <h2 class="font-display text-lg font-semibold">Feature permissions</h2>
    <p class="text-sm text-[var(--gs-muted)]">Only checked features will be available after the teacher signs in.</p>
    <ul class="space-y-3">
        @foreach ($teacherPermissions as $permission)
            <li>
                <label class="flex items-start gap-3 rounded-2xl border border-[var(--gs-line)] p-4">
                    <input
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->key }}"
                        class="mt-1 rounded border-[var(--gs-line)] text-[var(--gs-primary)]"
                        @checked(in_array($permission->key, $assignedKeys ?? [], true))
                    >
                    <span>
                        <span class="block font-semibold text-[var(--gs-ink)]">{{ $permission->name }}</span>
                        <span class="block text-sm text-[var(--gs-muted)]">{{ $permission->description }}</span>
                    </span>
                </label>
            </li>
        @endforeach
    </ul>
</section>
@endif
