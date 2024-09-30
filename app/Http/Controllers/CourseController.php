<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;
use App\Models\Assessment;

class CourseController extends Controller
{
    public function index()
    {
        // Get the currently authenticated user
        if (Auth::guard('web')->check()) {
            $student = Auth::guard('web')->user();
            $courses = $student->courses()->get();
            $userName = $student->name;
        } elseif (Auth::guard('teacher')->check()) {
            $teacher = Auth::guard('teacher')->user();
            $courses = $teacher->courses()->get();
            $userName = $teacher->name;
        } else {
            return redirect()->route('login');
        }

        // Pass the courses and user name to the view

        return view('pages.home', compact('courses', 'userName'));
    }

    public function show($courseCode)
    {
        // Find the course by course code
        $course = Course::with('students', 'teachers', 'assessments')
            ->where('course_code', $courseCode)
            ->firstOrFail();
    
        // Fetch the students that are not enrolled in the course
        $unenrolledStudents = Student::whereNotIn('id', $course->students->pluck('id'))->get();
    
        // Pass the course and related data, as well as unenrolled students, to the view
        return view('pages.course-details', compact('course', 'unenrolledStudents'));
    }
    
    public function enrollStudent($courseCode, $studentId)
    {
        // Find the course by course code
        $course = Course::where('course_code', $courseCode)->firstOrFail();
    
        // Find the student by student id
        $student = Student::findOrFail($studentId);
    
        // Check if the student is already enrolled in the course
        if ($course->students->contains($student->id)) {
            return redirect()->route('course-details', ['courseCode' => $courseCode])
                ->with('error', 'The student is already enrolled in this course.');
        }
    
        // Enroll the student in the course
        $course->students()->attach($student->id);
    
        return redirect()->route('course-details', ['courseCode' => $courseCode])
            ->with('success', 'Student enrolled in the course successfully!');
    }
    
    
}
