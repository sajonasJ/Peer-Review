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
            'sNumber' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate using the sNumber and password
        if (Auth::attempt(['snumber' => $request->sNumber, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home', absolute: false));
        }

        // If authentication fails, redirect back with errors
        return back()->withErrors([
            'sNumber' => 'The provided credentials do not match our records.',
        ])->onlyInput('sNumber');
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
