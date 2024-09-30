<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\Teacher;

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

    public function importCourseData(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'courseFile' => 'required|file|mimes:txt',
        ]);
    
        // Read the uploaded file
        $file = $request->file('courseFile');
        $fileContent = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
        // Parse the file content
        $courseData = [];
        foreach ($fileContent as $line) {
            $data = explode(',', $line);
            $courseData[] = array_map('trim', $data);
        }
    
        foreach ($courseData as $data) {
            if (count($data) < 12) {
                return redirect()->back()->withErrors(['courseFile' => 'Incomplete data found in the file. Each line must contain all required fields.']);
            }
    
            [$courseCode, $courseName, $teacherSnumber, $teacherName, $teacherEmail, $studentSnumber, $studentName, $studentEmail, $assessmentTitle, $assessmentInstruction, $assessmentDueDate, $assessmentDueTime, $assessmentType] = $data;
    
            // Check if the course already exists
            $course = Course::where('course_code', $courseCode)->first();
    
            if (!$course) {
                // Create the course if it doesn't exist
                $course = Course::create([
                    'course_code' => $courseCode,
                    'name' => $courseName,
                ]);
            } elseif ($course->name !== $courseName) {
                return redirect()->back()->withErrors(['courseFile' => "Mismatch: Course name for course code '$courseCode' does not match."]);
            }
    
            // Get the logged-in teacher
            $loggedInTeacher = Auth::guard('teacher')->user();
    
            // Attach the logged-in teacher to the course if not already attached
            if (!$course->teachers()->where('teacher_id', $loggedInTeacher->id)->exists()) {
                $course->teachers()->attach($loggedInTeacher->id);
            }
    
            // Check if the teacher already exists
            $teacher = Teacher::where('snumber', $teacherSnumber)->first();
            if (!$teacher) {
                // Create the teacher if they don't exist
                if (empty($teacherName) || empty($teacherEmail)) {
                    return redirect()->back()->withErrors(['courseFile' => "Missing data: Teacher information for '$teacherSnumber' is incomplete."]);
                }
    
                $teacher = Teacher::create([
                    'name' => $teacherName,
                    'email' => $teacherEmail,
                    'snumber' => $teacherSnumber,
                    'password' => bcrypt($teacherSnumber), // Password must be provided
                ]);
            } elseif ($teacher->name !== $teacherName || $teacher->email !== $teacherEmail) {
                return redirect()->back()->withErrors(['courseFile' => "Mismatch: Teacher information for snumber '$teacherSnumber' does not match."]);
            }
    
            // Attach the teacher to the course if not already attached
            if (!$course->teachers()->where('teacher_id', $teacher->id)->exists()) {
                $course->teachers()->attach($teacher->id);
            }
    
            // Check if the student already exists
            $student = Student::where('snumber', $studentSnumber)->first();
            if (!$student) {
                // Create the student if they don't exist
                if (empty($studentName) || empty($studentEmail)) {
                    return redirect()->back()->withErrors(['courseFile' => "Missing data: Student information for '$studentSnumber' is incomplete."]);
                }
    
                $student = Student::create([
                    'name' => $studentName,
                    'email' => $studentEmail,
                    'snumber' => $studentSnumber,
                    'password' => bcrypt($studentSnumber), // Password must be provided
                ]);
            } elseif ($student->name !== $studentName || $student->email !== $studentEmail) {
                return redirect()->back()->withErrors(['courseFile' => "Mismatch: Student information for snumber '$studentSnumber' does not match."]);
            }
    
            // Attach the student to the course if not already enrolled
            if (!$course->students()->where('student_id', $student->id)->exists()) {
                $course->students()->attach($student->id);
            }
    
            // Check if the assessment already exists for the course
            $existingAssessment = $course->assessments()->where('title', $assessmentTitle)->first();
            if (!$existingAssessment) {
                // Create assessment and associate it with the course
                if (empty($assessmentTitle) || empty($assessmentInstruction) || empty($assessmentDueDate) || empty($assessmentDueTime)) {
                    return redirect()->back()->withErrors(['courseFile' => "Missing data: Assessment information is incomplete."]);
                }
    
                $assessment = new Assessment([
                    'title' => $assessmentTitle,
                    'instruction' => $assessmentInstruction,
                    'num_reviews' => 3, // Adjust as needed
                    'max_score' => 100, // Adjust as needed
                    'due_date' => $assessmentDueDate,
                    'due_time' => $assessmentDueTime,
                    'type' => $assessmentType, // Use the assessment type from the file
                ]);
    
                $course->assessments()->save($assessment);
            }
        }
    
        return redirect()->route('home')->with('success', 'Course data imported successfully!');
    }
    
}
