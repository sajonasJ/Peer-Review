<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Assessment;

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

    public function show($courseCode, $assessmentId)
    {
        $course = Course::where('course_code', $courseCode)->firstOrFail();
        $assessment = $course->assessments()->where('id', $assessmentId)->firstOrFail();

        $reviewsReceived = $assessment->reviews()->where('reviewee_id', auth()->id())->get();
        $reviewsSent = $assessment->reviews()->where('reviewer_id', auth()->id())->get();

        return view('pages.assessment-details', compact('course', 'assessment', 'reviewsReceived', 'reviewsSent'));
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
}
