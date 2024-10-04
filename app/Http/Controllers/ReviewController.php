<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\Review;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    private function hasExistingReview($reviewerId, $studentId, $assessmentId)
    {
        return Review::where('reviewer_id', $reviewerId)
            ->where('reviewee_id', $studentId)
            ->where('assessment_id', $assessmentId)
            ->exists();
    }

    public function create($courseCode, $studentId, $assessmentId)
    {
        try {
            $course = Course::where('course_code', $courseCode)->firstOrFail();
            $student = Student::findOrFail($studentId);
            $assessment = $course->assessments()->findOrFail($assessmentId);

            // Check if the current student has already reviewed the target student for this assessment
            if ($this->hasExistingReview(auth()->id(), $studentId, $assessmentId)) {
                return redirect()->route('view-assessment', [
                    'courseCode' => $courseCode,
                    'assessmentId' => $assessmentId
                ])->with('error', 'You have already reviewed this student for this assessment.');
            }

            return view('pages.add-review', compact('course', 'student', 'assessment'));
        } catch (\Exception $e) {
            Log::error('Error loading review creation page', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return redirect()->route('view-assessment', [
                'courseCode' => $courseCode,
                'assessmentId' => $assessmentId
            ])->with('error', 'Failed to load the review creation page. Please try again.');
        }
    }

    public function store(Request $request, $courseCode, $studentId, $assessmentId)
    {
        $request->validate([
            'review' => 'required|string|min:5',
            'positive_feedback' => 'required|string|min:5',
            'improvement_feedback' => 'required|string|min:5',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            $course = Course::where('course_code', $courseCode)->firstOrFail();
            // $assessment = $course->assessments()->findOrFail($assessmentId);
            // $reviewee = $course->students()->findOrFail($studentId);

            // Prevent duplicate review submission
            if ($this->hasExistingReview(auth()->id(), $studentId, $assessmentId)) {
                return redirect()->route('view-assessment', [
                    'courseCode' => $courseCode,
                    'assessmentId' => $assessmentId
                ])->with('error', 'You have already reviewed this student for this assessment.');
            }

            // Concatenate review sections into a single review text
            $concatenatedReviewText = $request->review . "\n\n"
                . "What did the student do well?\n" . $request->positive_feedback . "\n\n"
                . "What could be improved?\n" . $request->improvement_feedback;

            // Create a new review
            Review::create([
                'review_text' => $concatenatedReviewText,
                'rating' => $request->rating,
                'reviewer_id' => auth()->id(),
                'reviewee_id' => $studentId,
                'assessment_id' => $assessmentId,
            ]);

            return redirect()->route('view-assessment', [
                'courseCode' => $courseCode,
                'assessmentId' => $assessmentId
            ])->with('success', 'Review submitted successfully!');
        } catch (\Exception $e) {
            Log::error('Error submitting review', [
                'user_id' => auth()->id(),
                'course_code' => $courseCode,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('view-assessment', [
                'courseCode' => $courseCode,
                'assessmentId' => $assessmentId
            ])->with('error', 'An error occurred while submitting your review. Please try again.');
        }
    }
}
