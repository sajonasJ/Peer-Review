<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assessment;

class AssessmentController extends Controller
{
    // Show the form for adding a new assessment
    public function create($courseCode)
    {
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        return view('pages.add-assessment', compact('course'));
    }

    // Store the new assessment in the database
    public function store(Request $request, $courseCode)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:20',
            'instructions' => 'required|string',
            'num_reviews' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date',
            'due_time' => 'required',
            'review_type' => 'required|in:student-select,teacher-assign',
            'workshop_week' => 'required|string',
        ]);

        // Find the course by course code
        $course = Course::where('course_code', $courseCode)->firstOrFail();

        // Create a new assessment for the course
        $course->assessments()->create([
            'title' => $request->title,
            'instruction' => $request->instructions,
            'num_reviews' => $request->num_reviews,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date,
            'due_time' => $request->due_time,
            'type' => $request->review_type,
        ]);

        // Redirect back to the course details page
        return redirect()->route('course-details', ['courseCode' => $courseCode])
            ->with('success', 'Assessment added successfully!');
    }
    public function show($courseCode, $assessmentId)
    {
        // Find the course by course code
        $course = Course::where('course_code', $courseCode)->firstOrFail();

        // Find the specific assessment by ID
        $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();

        // Get all reviews received for the assessment
        $reviewsReceived = $assessment->reviews()->where('reviewee_id', auth()->id())->get();

        // Get all reviews sent by the authenticated user for the assessment
        $reviewsSent = $assessment->reviews()->where('reviewer_id', auth()->id())->get();

        // Pass the course, assessment, and reviews to the view
        return view('pages.assessment-details', compact('course', 'assessment', 'reviewsReceived', 'reviewsSent'));
    }

    public function edit($courseCode, $assessmentId)
    {
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();
        return view('pages.add-assessment', compact('course', 'assessment'));
    }
}
