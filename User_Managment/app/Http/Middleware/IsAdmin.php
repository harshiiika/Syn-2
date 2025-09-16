<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     * If request is correct user can surpass middleware into the main file, if not they will be redirected to the login page.
     */

    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('/login')->withErrors(['email' => 'Admin access only.']);
        }

        return $next($request);
    }
}