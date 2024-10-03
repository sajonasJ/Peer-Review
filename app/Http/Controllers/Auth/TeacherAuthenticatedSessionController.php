<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class TeacherAuthenticatedSessionController extends Controller
{

    /**
     * Display the teaching login view.
     */
    public function create(): View
    {
        return view('pages.teaching-login'); // Use your custom teaching login view
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request data
        $request->validate([
            'snumber' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            // Attempt to authenticate using the 'teacher' guard
            if (Auth::guard('teacher')->attempt(['snumber' => $request->snumber, 'password' => $request->password], $request->filled('remember'))) {
                $request->session()->regenerate();

                // Redirect with a success message
                return redirect()->intended(route('home', absolute: false))
                    ->with('success', 'Logged in successfully! Welcome back, ' . Auth::guard('teacher')->user()->name . '!');
            }

            // If authentication fails
            return back()->with('error', 'The provided credentials do not match our records.')->onlyInput('snumber');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error during teacher login', ['error' => $e->getMessage()]);

            // Redirect back with an error message
            return back()->with('error', 'An unexpected error occurred during login. Please try again.');
        }
    }

    /**
     * Destroy an authenticated teacher session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            // Log out the teacher
            Auth::guard('teacher')->logout();

            // Invalidate the session
            $request->session()->invalidate();

            // Regenerate the session token
            $request->session()->regenerateToken();

            // Redirect to the login page with a success message
            return redirect('/')->with('success', 'Logged out successfully. Come back soon!');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error during teacher logout', ['error' => $e->getMessage()]);

            // Redirect to the home page with an error message
            return redirect('/')->with('error', 'An error occurred during logout. Please try again.');
        }
    }
}
