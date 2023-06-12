<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->hasRole('USER')) {
                // If user is a USER proceed with request
                return $next($request);
            } else if ($user->hasRole('MODERATOR')) {
                if ($request->expectsJson()) {
                    return response('', 403);
                } else {
                    // If user is not a USER redirect to moderator dashboard
                    return redirect(route('admin.dashboard'))->with('info', 'Insufficient permissions...');
                }
            }
        }

        // Permission denied
        abort(403);
    }
}
