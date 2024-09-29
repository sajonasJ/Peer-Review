<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Review;

class ReviewController extends Controller
{
    // Show the form for adding a new review for a specific student
    public function create($courseCode, $studentId)
    {
        // Get the course and student details
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $student = Student::findOrFail($studentId);

        // Pass the data to the view
        return view('pages.add-review', compact('course', 'student'));
    }

    // Store the new review in the database
    public function store(Request $request, $courseCode, $studentId)
    {
        // Validate the request data
        $request->validate([
            'review' => 'required|string|min:5',
        ]);

        // Create a new review
        Review::create([
            'review_text' => $request->review,
            'rating' => 5, // Example: default rating (you can change this)
            'reviewer_id' => auth()->id(),
            'reviewee_id' => $studentId,
            'assessment_id' => $request->input('assessment_id'), // Assuming assessment ID is passed
        ]);

        // Redirect back to the course details page
        return redirect()->route('course-details', ['courseCode' => $courseCode])
            ->with('success', 'Review submitted successfully!');
    }
}
