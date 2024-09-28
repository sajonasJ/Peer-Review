<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
// use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('pages.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'snumber' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate using the 'web' guard which is configured for students
        if (Auth::guard('web')->attempt(['snumber' => $request->snumber, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirect to intended page after successful login
            return redirect()->intended('/home');
        }

        // If authentication fails, redirect back with errors
        return back()->withErrors([
            'snumber' => 'The provided credentials do not match our records.',
        ])->onlyInput('snumber');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
