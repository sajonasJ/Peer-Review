<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('pages.login');
    }

    public function store(Request $request): RedirectResponse
    {
        // Validation with custom error handling
        Auth::guard('web')->logout();
        Auth::guard('teacher')->logout();
        try {
            $request->validate([
                'snumber' => 'required|string',
                'password' => 'required|string',
            ]);
        } catch (ValidationException $e) {
            return back()
                ->with('error', 'Validation failed: Please provide both student number and password correctly.')
                ->onlyInput('snumber');
        }

        // Attempt authentication
        try {
            if (Auth::guard('web')->attempt([
                'snumber' => $request->snumber,
                'password' => $request->password
            ], $request->filled('remember'))) {
                $request->session()->regenerate();

                return redirect()
                    ->intended('/home')
                    ->with('success', 'Logged in successfully! Welcome back, ' . Auth::user()->name . '!');
            }

            // Authentication failed
            return back()
                ->with('error', 'The provided credentials do not match our records.')
                ->onlyInput('snumber');
        } catch (\Exception $e) {
            // Unexpected error during authentication
            return back()
                ->with('error', 'An unexpected error occurred during login. Please try again.');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            Auth::guard('web')->logout();
            Auth::guard('teacher')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')
                ->with('success', 'Logged out successfully.');
        } catch (\Exception $e) {
            return redirect('/')
                ->with('error', 'An error occurred during logout. Please try again.');
        }
    }
}
