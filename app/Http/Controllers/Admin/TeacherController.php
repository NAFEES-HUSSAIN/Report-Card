<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use App\Support\SystemPermissions;
use App\Support\TableSort;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminTeachers), 403);

        $search = $request->string('q')->trim()->toString();
        $status = $request->string('status')->toString();

        $sortColumns = [
            'name' => 'name',
            'username' => 'username',
            'email' => 'email',
            'status' => 'is_active',
            'created' => 'created_at',
        ];

        [$sort, $direction] = TableSort::from($request, $sortColumns, 'name');

        $teachers = User::query()
            ->where('role', UserRole::Teacher)
            ->with('permissions')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($inner) use ($search): void {
                    $inner
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false));

        $teachers = TableSort::apply($teachers, $request, $sortColumns, 'name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.teachers.index', [
            'shellRole' => 'admin',
            'teachers' => $teachers,
            'search' => $search,
            'status' => $status,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function create(): View
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminTeachers), 403);

        return view('admin.teachers.create', [
            'shellRole' => 'admin',
            'teacherPermissions' => Permission::query()
                ->whereIn('key', SystemPermissions::teacherKeys())
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminTeachers), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9._-]+$/', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'is_active' => ['sometimes', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(SystemPermissions::teacherKeys())],
        ]);

        $teacher = User::query()->create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => UserRole::Teacher,
            'is_active' => $request->boolean('is_active'),
            'email_verified_at' => now(),
        ]);

        if (auth()->user()?->hasPermission(SystemPermissions::AdminPermissions)) {
            $teacher->syncPermissionKeys($validated['permissions'] ?? []);
        }

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher account created.');
    }

    public function edit(User $teacher): View
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminTeachers), 403);
        abort_unless($teacher->role === UserRole::Teacher, 404);

        $teacher->load('permissions');

        return view('admin.teachers.edit', [
            'shellRole' => 'admin',
            'teacher' => $teacher,
            'teacherPermissions' => Permission::query()
                ->whereIn('key', SystemPermissions::teacherKeys())
                ->orderBy('name')
                ->get(),
            'assignedKeys' => $teacher->permissions->pluck('key')->all(),
        ]);
    }

    public function update(Request $request, User $teacher): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminTeachers), 403);
        abort_unless($teacher->role === UserRole::Teacher, 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9._-]+$/', Rule::unique('users', 'username')->ignore($teacher->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($teacher->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_active' => ['sometimes', 'boolean'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(SystemPermissions::teacherKeys())],
        ]);

        $teacher->fill([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'is_active' => $request->boolean('is_active'),
        ]);

        if (filled($validated['password'] ?? null)) {
            $teacher->password = $validated['password'];
        }

        $teacher->save();

        if (auth()->user()?->hasPermission(SystemPermissions::AdminPermissions)) {
            $teacher->syncPermissionKeys($validated['permissions'] ?? []);
        }

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(User $teacher): RedirectResponse
    {
        abort_unless(auth()->user()?->hasPermission(SystemPermissions::AdminTeachers), 403);
        abort_unless($teacher->role === UserRole::Teacher, 404);

        $teacher->delete();

        return redirect()
            ->route('admin.teachers.index')
            ->with('success', 'Teacher removed.');
    }
}
