<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TeacherAuthenticatedSessionController extends Controller
{
    /**
     * Display the teacher login view.
     */
    public function create(): View
    {
        return view('pages.teaching-login'); // Use your custom teaching login view
    }

    /**
     * Handle an incoming authentication request for teachers.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'snumber' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate using the 'teacher' guard
        if (Auth::guard('teacher')->attempt(['snumber' => $request->snumber, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('home', absolute: false));
        }

        // If authentication fails, redirect back with errors
        return back()->withErrors([
            'snumber' => 'The provided credentials do not match our records.',
        ])->onlyInput('snumber');
    }

    /**
     * Destroy an authenticated teacher session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('teacher')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
