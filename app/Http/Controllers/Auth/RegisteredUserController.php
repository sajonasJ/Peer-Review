<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('pages.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    // Validate the request data
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:students,email'],
        'snumber' => ['required', 'string', 'regex:/^s[0-9]{7}$/', 'unique:students,snumber'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // Add a log message after successful validation
    logger()->info('Validation passed, creating student...', ['email' => $request->email]);

    // Create the student
    $student = Student::create([
        'name' => $request->name,
        'email' => $request->email,
        'snumber' => $request->snumber,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($student));

    Auth::login($student);

    return redirect()->route('home');
}

}
