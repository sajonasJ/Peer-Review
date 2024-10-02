<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assessment;
use App\Models\Student;
use App\Models\Review;

class AssessmentController extends Controller
{
    public function create($courseCode)
    {
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        return view('pages.add-assessment', compact('course'));
    }

    public function store(Request $request, $courseCode)
    {
        $request->validate([
            'title' => 'required|string|max:20',
            'instructions' => 'required|string',
            'num_reviews' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date',
            'due_time' => 'required',
            'review_type' => 'required|in:student-select,teacher-assign',
        ]);

        $course = Course::where('course_code', $courseCode)->firstOrFail();

        $course->assessments()->create([
            'title' => $request->title,
            'instruction' => $request->instructions,
            'num_reviews' => $request->num_reviews,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date,
            'due_time' => $request->due_time,
            'type' => $request->review_type,
        ]);

        return redirect()->route('course-details', ['courseCode' => $courseCode])
            ->with('success', 'Assessment added successfully!');
    }

    public function show($courseCode, $assessmentId, Request $request)
    {
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();

        // Paginate students with 10 students per page
        $students = $course->students()->paginate(10);

        // Get reviews received and sent for authenticated user for the specific assessment
        $reviewsReceived = $assessment->reviews()
            ->where('reviewee_id', auth()->id())
            ->where('assessment_id', $assessment->id)
            ->get();

        $reviewsSent = $assessment->reviews()
            ->where('reviewer_id', auth()->id())
            ->where('assessment_id', $assessment->id)
            ->get();

        // Additional data for the selected student
        $selectedStudent = null;
        $studentReviewsReceived = collect();
        $studentReviewsSent = collect();

        $studentId = $request->input('studentId');

        if ($studentId) {
            $selectedStudent = Student::findOrFail($studentId);

            // Fetch reviews received and sent by the selected student, but only for the given assessment
            $studentReviewsReceived = $selectedStudent->reviewsReceived()
                ->where('assessment_id', $assessment->id)
                ->get();

            $studentReviewsSent = $selectedStudent->reviewsGiven()
                ->where('assessment_id', $assessment->id)
                ->get();
        }

        // Get the total count of reviews for the assessment
        $reviewCount = $assessment->reviews()->count();

        return view('pages.assessment-details', compact(
            'course',
            'assessment',
            'students', // Paginated students
            'reviewsReceived',
            'reviewsSent',
            'selectedStudent',
            'studentReviewsReceived',
            'studentReviewsSent',
            'reviewCount'
        ));
    }


    public function edit($courseCode, $assessmentId)
    {
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();
        return view('pages.add-assessment', compact('course', 'assessment'));
    }

    public function update(Request $request, $courseCode, $assessmentId)
    {
        $request->validate([
            'title' => 'required|string|max:20',
            'instructions' => 'required|string',
            'num_reviews' => 'required|integer|min:1',
            'max_score' => 'required|integer|min:1|max:100',
            'due_date' => 'required|date',
            'due_time' => 'required',
            'review_type' => 'required|in:student-select,teacher-assign',
        ]);

        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();

        $assessment->update([
            'title' => $request->title,
            'instruction' => $request->instructions,
            'num_reviews' => $request->num_reviews,
            'max_score' => $request->max_score,
            'due_date' => $request->due_date,
            'due_time' => $request->due_time,
            'type' => $request->review_type,
        ]);

        return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId])
            ->with('success', 'Assessment updated successfully!');
    }
    public function assignReviewee(Request $request, $courseCode, $assessmentId)
    {
        $assessment = Assessment::where('id', $assessmentId)->firstOrFail();
        $studentId = $request->input('student_id');

        // Ensure the student is enrolled in the course
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $student = $course->students()->findOrFail($studentId);

        // Check if the student is already a reviewee for this assessment
        if (!$assessment->reviews()->where('reviewee_id', $student->id)->exists()) {
            Review::create([
                'assessment_id' => $assessment->id,
                'reviewee_id' => $student->id,
                'review_text' => '',
                'rating' => 0, // Set initial rating to 0
            ]);
        }

        return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId]);
    }


    public function assignReviewer(Request $request, $courseCode, $assessmentId)
    {
        $assessment = Assessment::where('id', $assessmentId)->firstOrFail();
        $studentId = $request->input('student_id');

        // Ensure the student is enrolled in the course
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $student = $course->students()->findOrFail($studentId);

        // Check if the student is already a reviewer for this assessment
        if (!$assessment->reviews()->where('reviewer_id', $student->id)->exists()) {
            Review::create([
                'assessment_id' => $assessment->id,
                'reviewer_id' => $student->id,
                'review_text' => '',
                'rating' => 0, // Set initial rating to 0
            ]);
        }

        return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId]);
    }

    public function viewAssessment($courseCode, $assessmentId)
    {
        // Get the course and the assessment
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();

        // Paginate students enrolled in the course with 10 students per page
        $students = $course->students()->paginate(10);

        // Get reviews sent and received for the authenticated user for the specific assessment
        $reviewsReceived = $assessment->reviews()
            ->where('reviewee_id', auth()->id())
            ->get();

        $reviewsSent = $assessment->reviews()
            ->where('reviewer_id', auth()->id())
            ->get();

        return view('pages.view-assessments', compact(
            'course',
            'assessment',
            'students', // Include the students
            'reviewsReceived',
            'reviewsSent'
        ));
    }
}
