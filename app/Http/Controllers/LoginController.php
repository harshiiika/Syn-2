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
            
            $request->session()->regenerate();
            
            return redirect()->route('dashboard');
        }

        Log::warning('Admin login failed: invalid credentials');
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }


    /**
     * Logs out the user but keeps all data in database (MongoDB)
     * Session data is cleared, but database records remain intact
     */
    public function logout(Request $request)
    {
        Log::info('User logout initiated', [
            'user_email' => Auth::guard('admin')->user()->email ?? 'Unknown',
            'user_id' => Auth::guard('admin')->id() ?? 'Unknown'
        ]);

        // Logout from admin guard (clears session only, NOT database)
        Auth::guard('admin')->logout();

        // Invalidate the current session (security best practice)
        $request->session()->invalidate();

        // Regenerate CSRF token to prevent CSRF attacks
        $request->session()->regenerateToken();

        Log::info('User logged out successfully');

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'You have been logged out successfully!');
    }

}