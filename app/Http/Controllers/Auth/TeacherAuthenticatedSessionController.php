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
            'staffNumber' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to authenticate using the 'teacher' guard and the 'staffNumber' field
        if (Auth::guard('teacher')->attempt(['staffNumber' => $request->staffNumber, 'password' => $request->password], $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
        }

        // If authentication fails, redirect back with errors
        return back()->withErrors([
            'staffNumber' => 'The provided credentials do not match our records.',
        ])->onlyInput('staffNumber');
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
