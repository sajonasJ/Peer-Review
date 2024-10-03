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
    // Retrieve the course to use in validation
    $course = Course::where('course_code', $courseCode)->firstOrFail();

    // Validate the input fields (excluding the unique check)
    $request->validate([
        'title' => 'required|string|max:20',
        'instructions' => 'required|string',
        'num_reviews' => 'required|integer|min:1',
        'max_score' => 'required|integer|min:1|max:100',
        'due_date' => 'required|date',
        'due_time' => 'required',
        'review_type' => 'required|in:student-select,teacher-assign',
    ]);

    // Custom check: Ensure the assessment title is unique within the course
    $existingAssessment = $course->assessments()->where('title', $request->title)->first();
    if ($existingAssessment) {
        // Redirect back with an error message if an assessment with the same title already exists
        return redirect()->route('course-details', ['courseCode' => $courseCode])
            ->with('error', 'An assessment with the same title already exists for this course. Please use a different title.');
    }

    try {
        // Create the assessment for the course
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
    } catch (\Exception $e) {
        return redirect()->route('course-details', ['courseCode' => $courseCode])
            ->with('error', 'Failed to add assessment. Please try again.');
    }
}


    public function show($courseCode, $assessmentId, Request $request)
    {
        // Fetching course and assessment, with error handling for missing entries
        try {
            $course = Course::where('course_code', $courseCode)->firstOrFail();
            $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Assessment or course not found.');
        }

        // Paginate students with 10 students per page
        $students = $course->students()->paginate(10);

        // Get reviews received and sent for authenticated user for the specific assessment
        $reviewsReceived = $assessment->reviews()->where('reviewee_id', auth()->id())->get();
        $reviewsSent = $assessment->reviews()->where('reviewer_id', auth()->id())->get();

        // Additional data for the selected student
        $selectedStudent = null;
        $studentReviewsReceived = collect();
        $studentReviewsSent = collect();

        $studentId = $request->input('studentId');
        if ($studentId) {
            try {
                $selectedStudent = Student::findOrFail($studentId);
                $studentReviewsReceived = $selectedStudent->reviewsReceived()->where('assessment_id', $assessment->id)->get();
                $studentReviewsSent = $selectedStudent->reviewsGiven()->where('assessment_id', $assessment->id)->get();
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Selected student not found.');
            }
        }

        $reviewCount = $assessment->reviews()->count();

        return view('pages.assessment-details', compact(
            'course',
            'assessment',
            'students',
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
        try {
            $course = Course::where('course_code', $courseCode)->firstOrFail();
            $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();
            return view('pages.add-assessment', compact('course', 'assessment'));
        } catch (\Exception $e) {
            return redirect()->route('course-details', ['courseCode' => $courseCode])
                ->with('error', 'Failed to find assessment for editing.');
        }
    }

    public function update(Request $request, $courseCode, $assessmentId)
{
    // Validate the input fields
    $request->validate([
        'title' => 'required|string|max:20',
        'instructions' => 'required|string',
        'num_reviews' => 'required|integer|min:1',
        'max_score' => 'required|integer|min:1|max:100',
        'due_date' => 'required|date',
        'due_time' => 'required',
        'review_type' => 'required|in:student-select,teacher-assign',
    ]);

    try {
        // Retrieve the course and assessment
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();

        // Custom check: Ensure the assessment title is unique within the course (excluding the current assessment)
        $existingAssessment = $course->assessments()
            ->where('title', $request->title)
            ->where('id', '!=', $assessmentId)
            ->first();

        if ($existingAssessment) {
            // Redirect back with an error message if an assessment with the same title already exists
            return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId])
                ->with('error', 'An assessment with the same title already exists for this course. Please use a different title.');
        }

        // Update the assessment details
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
    } catch (\Exception $e) {
        return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId])
            ->with('error', 'Failed to update assessment. Please try again.');
    }
}

    public function assignReviewee(Request $request, $courseCode, $assessmentId)
    {
        try {
            $assessment = Assessment::where('id', $assessmentId)->firstOrFail();
            $studentId = $request->input('student_id');

            $course = Course::where('course_code', $courseCode)->firstOrFail();
            $student = $course->students()->findOrFail($studentId);

            if (!$assessment->reviews()->where('reviewee_id', $student->id)->exists()) {
                Review::create([
                    'assessment_id' => $assessment->id,
                    'reviewee_id' => $student->id,
                    'review_text' => '',
                    'rating' => 0,
                ]);
            }

            return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId])
                ->with('success', 'Reviewee assigned successfully!');
        } catch (\Exception $e) {
            return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId])
                ->with('error', 'Failed to assign reviewee.');
        }
    }

    public function assignReviewer(Request $request, $courseCode, $assessmentId)
    {
        try {
            $assessment = Assessment::where('id', $assessmentId)->firstOrFail();
            $studentId = $request->input('student_id');

            $course = Course::where('course_code', $courseCode)->firstOrFail();
            $student = $course->students()->findOrFail($studentId);

            if (!$assessment->reviews()->where('reviewer_id', $student->id)->exists()) {
                Review::create([
                    'assessment_id' => $assessment->id,
                    'reviewer_id' => $student->id,
                    'review_text' => '',
                    'rating' => 0,
                ]);
            }

            return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId])
                ->with('success', 'Reviewer assigned successfully!');
        } catch (\Exception $e) {
            return redirect()->route('assessment-details', ['courseCode' => $courseCode, 'assessmentId' => $assessmentId])
                ->with('error', 'Failed to assign reviewer.');
        }
    }

    public function viewAssessment($courseCode, $assessmentId)
    {
        try {
            $course = Course::where('course_code', $courseCode)->firstOrFail();
            $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();

            $students = $course->students()->paginate(10);

            $reviewsReceived = $assessment->reviews()->where('reviewee_id', auth()->id())->get();
            $reviewsSent = $assessment->reviews()->where('reviewer_id', auth()->id())->get();

            return view('pages.view-assessments', compact(
                'course',
                'assessment',
                'students',
                'reviewsReceived',
                'reviewsSent'
            ));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Failed to load assessment details.');
        }
    }
}
