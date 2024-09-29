<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;

class CourseController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        if (Auth::guard('web')->check()) {
            $student = Auth::guard('web')->user();
            $courses = $student->courses()->get();
        } elseif (Auth::guard('teacher')->check()) {
            $teacher = Auth::guard('teacher')->user();
            // Logic for fetching courses for the teacher, if applicable
            $courses = $teacher->courses()->get();
        } else {
            return redirect()->route('login');
        }

        // Pass the courses to the view
        return view('pages.home', compact('courses'));
    }
}
