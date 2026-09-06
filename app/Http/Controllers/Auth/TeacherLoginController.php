<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TeacherLoginController extends Controller
{
    public function create(): View
    {
        return view('auth.teacher-login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (! Auth::attempt([$field => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'login' => __('These credentials do not match our records.'),
            ]);
        }

        $user = Auth::user();

        if ($user === null || $user->role !== UserRole::Teacher) {
            Auth::logout();

            throw ValidationException::withMessages([
                'login' => __('Only teachers can sign in here. Admins should use the admin portal.'),
            ]);
        }

        if (! $user->is_active) {
            Auth::logout();

            throw ValidationException::withMessages([
                'login' => __('Your account is inactive. Ask the principal/admin to activate it and assign permissions.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('teacher.dashboard'));
    }
}
