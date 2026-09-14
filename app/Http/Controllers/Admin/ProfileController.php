<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\SystemPermissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(?User $user = null): View
    {
        $actor = auth()->user();
        abort_unless($actor !== null, 403);

        $target = $user ?? $actor;

        if ($target->is($actor)) {
            // own profile always allowed when authenticated as staff
        } else {
            abort_unless($actor->hasPermission(SystemPermissions::AdminProfiles), 403);
        }

        return view('admin.profile.edit', [
            'shellRole' => $actor->isAdmin() ? 'admin' : 'teacher',
            'profileUser' => $target->fresh(),
            'canEditRole' => $actor->isAdmin() && ! $target->is($actor),
        ]);
    }

    public function update(Request $request, ?User $user = null): RedirectResponse
    {
        $actor = auth()->user();
        abort_unless($actor !== null, 403);

        $target = $user ?? $actor;
        $editingSelf = $target->is($actor);

        if (! $editingSelf) {
            abort_unless($actor->hasPermission(SystemPermissions::AdminProfiles), 403);
        }

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9._-]+$/', Rule::unique('users', 'username')->ignore($target->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($target->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'remove_avatar' => ['sometimes', 'boolean'],
        ];

        if ($actor->isAdmin() && ! $editingSelf) {
            $rules['role'] = ['required', Rule::in([UserRole::Teacher->value, UserRole::Admin->value])];
            $rules['is_active'] = ['sometimes', 'boolean'];
        }

        $validated = $request->validate($rules);

        $target->name = $validated['name'];
        $target->username = $validated['username'];
        $target->email = $validated['email'];

        if ($actor->isAdmin() && ! $editingSelf) {
            $target->role = UserRole::from($validated['role']);
            $target->is_active = $request->boolean('is_active');
        }

        if (filled($validated['password'] ?? null)) {
            $target->password = $validated['password'];
        }

        if ($request->boolean('remove_avatar') && ! $request->hasFile('avatar')) {
            $target->deleteStoredAvatar();
        }

        if ($request->hasFile('avatar')) {
            $target->deleteStoredAvatar();
            $target->avatar_path = $request->file('avatar')->store('avatars', 'public');
        }

        $target->save();

        if ($editingSelf) {
            // Re-login so the next request (and topbar) use the fresh avatar_path.
            Auth::login($target->fresh());
            $actor = auth()->user();
        }

        $route = $editingSelf
            ? ($actor->isAdmin() ? 'admin.profile.edit' : 'teacher.profile.edit')
            : 'admin.users.profile.edit';

        return redirect()
            ->route($route, $editingSelf ? [] : ['user' => $target])
            ->with('success', 'Profile updated successfully.');
    }
}
