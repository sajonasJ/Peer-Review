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

    //display page
    public function create(): View
    {
        return view('pages.teaching-login');
    }

    //login function
    public function store(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('teacher')->logout();
        // Validate the request data
        $request->validate([
            'snumber' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::guard('teacher')->attempt(['snumber' => $request->snumber, 'password' => $request->password], $request->filled('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended(route('home', absolute: false))
                    ->with('success', 'Logged in successfully! Welcome back, ' . Auth::guard('teacher')->user()->name . '!');
            }
            return back()->with('error', 'The provided credentials do not match our records.')->onlyInput('snumber');

        } catch (\Exception $e) {

            Log::error('Error during teacher login', ['error' => $e->getMessage()]);

            return back()->with('error', 'An unexpected error occurred during login. Please try again.');
        }
    }

    //logout
    public function destroy(Request $request): RedirectResponse
    {
        try {
            Auth::guard('web')->logout();
            Auth::guard('teacher')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Logged out successfully. Come back soon!');
        } catch (\Exception $e) {

            Log::error('Error during teacher logout', ['error' => $e->getMessage()]);

            return redirect('/')->with('error', 'An error occurred during logout. Please try again.');
        }
    }
}
