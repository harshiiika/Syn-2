<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    /**
     * Handle the login request
     * Checks Login info, credentials, and redirects accordingly.
     */
public function login(Request $request)
{
    Log::info('Login attempt started');

    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::guard('admin')->attempt($request->only('email', 'password'))) {
        Log::info('Admin login successful');
        return redirect()->route('dashboard');
    }

    Log::warning('Admin login failed: invalid credentials');
    return back()->withErrors([
        'email' => 'Invalid email or password.',
    ]);
}

}