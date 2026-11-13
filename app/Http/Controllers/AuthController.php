<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Handles login and logout for Barangay Bulletin.
 *
 * Laravel's built-in session authentication is used
 * instead of manually storing passwords in sessions.
 */
class AuthController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Validate login credentials and authenticate.
     */
    public function login(Request $request)
    {
        // Validate the submitted email and password.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Auth::attempt checks the user record and
        // verifies the hashed password automatically.
        if (Auth::attempt($credentials)) {

            // Generate a new session ID to help prevent
            // session fixation attacks after login.
            $request->session()->regenerate();

            // Redirect to the original protected page
            // or to the bulletin listing by default.
            return redirect()->intended(route('posts.index'))
                ->with('success', 'Welcome back to Barangay Bulletin!');
        }

        // Return an error without revealing whether
        // the email or password was incorrect.
        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
            ->onlyInput('email');
    }

    /**
     * Log out the authenticated user.
     */
    public function logout(Request $request)
    {
        // Remove the user from Laravel's authentication.
        Auth::logout();

        // Invalidate the old session.
        $request->session()->invalidate();

        // Generate a new CSRF token for the next session.
        $request->session()->regenerateToken();

        // Return to the public bulletin page.
        return redirect()
            ->route('posts.index')
            ->with('success', 'You have been logged out successfully.');
    }
}
