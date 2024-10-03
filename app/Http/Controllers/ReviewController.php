<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Review;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function create($courseCode, $studentId, $assessmentId)
    {
        try {
            // Get the course, student, and assessment details
            $course = Course::where('course_code', $courseCode)->firstOrFail();
            $student = Student::findOrFail($studentId);
            $assessment = $course->assessments()->findOrFail($assessmentId);

            // Pass the data to the view
            return view('pages.add-review', compact('course', 'student', 'assessment'));
        } catch (\Exception $e) {
            Log::error('Error loading review creation page', ['error' => $e->getMessage()]);
            return redirect()->route('view-assessment', [
                'courseCode' => $courseCode,
                'assessmentId' => $assessmentId
            ])->with('error', 'Failed to load the review creation page. Please try again.');
        }
    }

    public function store(Request $request, $courseCode, $studentId, $assessmentId)
    {
        // Validate the request data including review text and rating
        $request->validate([
            'review' => 'required|string|min:5',
            'rating' => 'required|integer|min:1|max:5', // Ensure rating is between 1 and 5
        ]);

        try {
            // Fetch the course and assessment
            $course = Course::where('course_code', $courseCode)->firstOrFail();
            $assessment = $course->assessments()->findOrFail($assessmentId);

            // Ensure the student being reviewed exists in the course
            $reviewee = $course->students()->findOrFail($studentId);

            // Check if the current student (logged-in user) has already reviewed the target student for this assessment
            $existingReview = Review::where('reviewer_id', auth()->id())
                ->where('reviewee_id', $studentId)
                ->where('assessment_id', $assessmentId)
                ->first();

            if ($existingReview) {
                return redirect()->route('view-assessment', [
                    'courseCode' => $courseCode,
                    'assessmentId' => $assessmentId
                ])->with('error', 'You have already reviewed this student for this assessment.');
            }

            // Check if the number of reviews already meets or exceeds the allowed limit for this assessment
            $totalReviews = Review::where('assessment_id', $assessmentId)->count();
            if ($totalReviews >= $assessment->num_reviews) {
                return redirect()->route('view-assessment', [
                    'courseCode' => $courseCode,
                    'assessmentId' => $assessmentId
                ])->with('error', 'The maximum number of reviews for this assessment has been reached.');
            }

            // Create a new review
            Review::create([
                'review_text' => $request->review,
                'rating' => $request->rating,
                'reviewer_id' => auth()->id(),
                'reviewee_id' => $studentId,
                'assessment_id' => $assessmentId,
            ]);

            // Redirect back to the assessment view page with a success message
            return redirect()->route('view-assessment', [
                'courseCode' => $courseCode,
                'assessmentId' => $assessmentId
            ])->with('success', 'Review submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Error submitting review', ['error' => $e->getMessage()]);
            return redirect()->route('view-assessment', [
                'courseCode' => $courseCode,
                'assessmentId' => $assessmentId
            ])->with('error', 'An error occurred while submitting your review. Please try again.');
        }
    }
}
