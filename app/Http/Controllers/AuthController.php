<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Student;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'sNumber' => 'required|string',
            'password' => 'required|string',
        ]);

        // Attempt to find the student by student number
        $student = Student::where('snumber', $request->sNumber)->first();

        if ($student && Hash::check($request->password, $student->password)) {
            // Manually log in the user
            Auth::guard('web')->login($student);

            // Redirect to the home page with a success message
            return redirect()->route('home')
                ->with('success', 'Logged in successfully! Welcome back, ' . $student->name . '!');
        }

        // If the credentials do not match, redirect back to login with error message
        return redirect()->route('login')
            ->with('error', 'Invalid credentials, please try again.');
    }
}
