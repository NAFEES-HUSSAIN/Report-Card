<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        $allowed = collect($roles)
            ->map(fn (string $role) => UserRole::tryFrom($role))
            ->filter()
            ->all();

        if ($allowed === [] || ! in_array($user->role, $allowed, true)) {
            abort(403, 'You are not authorized to access this area.');
        }

        return $next($request);
    }
}
