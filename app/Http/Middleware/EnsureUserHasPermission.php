<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->route('splash');
        }

        if (! $user->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this feature. Ask your principal/admin.');
        }

        return $next($request);
    }
}
