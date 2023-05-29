<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModeratorAuthenticated
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

            if ($user->hasRole('MODERATOR')) {
                // If user is a MODERATOR proceed with request
                return $next($request);
            } else if ($user->hasRole('USER')) {
                // If user is not a MODERATOR redirect to dashboard
                return redirect(route('user-profile'))->with('info', 'Insufficient permissions...');;
            }
        }

        // Permission denied
        abort(403);
    }
}
