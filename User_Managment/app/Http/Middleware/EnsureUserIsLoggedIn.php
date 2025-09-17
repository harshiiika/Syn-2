<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
class EnsureUserIsLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('logged_in')) {
            return redirect('/login');
        }
        return $next($request);
    }
}
