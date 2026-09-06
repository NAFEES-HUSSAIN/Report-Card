<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentSession
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('student_id')) {
            return redirect()
                ->route('student.lookup')
                ->with('error', 'Enter your index number to continue.');
        }

        return $next($request);
    }
}
