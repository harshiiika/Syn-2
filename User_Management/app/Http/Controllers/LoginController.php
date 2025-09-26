<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function login(Request $request)
    {
        Log::info('Login attempt started');

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Use default guard with your Login model
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate(); // important
            Log::info('Login successful');
            return redirect()->route('dashboard');
        }

        Log::warning('Login failed: invalid credentials');
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
