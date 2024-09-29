<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create($courseCode, $studentId, $assessmentId)
    {
        // Get the course, student, and assessment details
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $student = Student::findOrFail($studentId);
        $assessment = $course->assessments()->findOrFail($assessmentId);

        // Pass the data to the view
        return view('pages.add-review', compact('course', 'student', 'assessment'));
    }

    public function store(Request $request, $courseCode, $studentId, $assessmentId)
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
            'assessment_id' => $assessmentId,
        ]);

        // Redirect back to the course details page
        return redirect()->route('course-details', ['courseCode' => $courseCode])
            ->with('success', 'Review submitted successfully!');
    }
}
