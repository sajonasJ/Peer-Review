<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Course;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

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
        $course = Course::with('teachers', 'assessments')
            ->where('course_code', $courseCode)
            ->firstOrFail();

        // Fetch enrolled students with pagination
        $enrolledStudents = $course->students()->paginate(10);

        // Fetch the students that are not enrolled in the course with pagination
        $unenrolledStudents = Student::whereNotIn('id', $course->students->pluck('id'))->paginate(10);

        // Pass the course and related data, as well as unenrolled students, to the view
        return view('pages.course-details', compact('course', 'enrolledStudents', 'unenrolledStudents'));
    }


    public function enrollStudent($courseCode, $studentId)
    {
        try {
            // Find the course by course code
            $course = Course::where('course_code', $courseCode)->firstOrFail();

            // Find the student by student id
            $student = Student::findOrFail($studentId);

            // Check if the student is already enrolled in the course
            if ($course->students->contains($student->id)) {
                session()->flash('error', 'The student is already enrolled in this course.');
                return redirect()->route('course-details', ['courseCode' => $courseCode]);
            }

            // Enroll the student in the course
            $course->students()->attach($student->id);

            session()->flash('success', 'Student enrolled in the course successfully!');
            return redirect()->route('course-details', ['courseCode' => $courseCode]);
        } catch (\Exception $e) {
            Log::error('Failed to enroll student', ['error' => $e->getMessage()]);
            session()->flash('error', 'An error occurred while enrolling the student.');
            return redirect()->route('course-details', ['courseCode' => $courseCode]);
        }
    }
    public function importCourseData(Request $request)
    {
        try {
            $request->validate([
                'courseFile' => 'required|file|mimes:json',
            ]);

            // Read the uploaded file
            $file = $request->file('courseFile');
            $fileContent = file_get_contents($file->getRealPath());
            Log::info('Uploaded File Content:', ['content' => $fileContent]);

            $courseData = json_decode($fileContent, true);
            if (is_null($courseData)) {
                Log::error('JSON Decode Error', ['error' => json_last_error_msg(), 'content' => $fileContent]);
                session()->flash('error', 'Failed to decode JSON.');
                return redirect()->back();
            }

            foreach ($courseData as $data) {
                // Validate that each data entry has the required fields
                if (!isset(
                    $data['course_code'],
                    $data['course_name'],
                    $data['teachers'],
                    $data['students'],
                    $data['assessments']
                )) {
                    session()->flash('error', 'Incomplete data found in the JSON file.');
                    return redirect()->back();
                }

                $courseCode = $data['course_code'];

                // Check if the course already exists
                $course = Course::where('course_code', $courseCode)->first();
                if ($course) {
                    // If the course already exists, return with an error
                    session()->flash('error', "A course with code '{$courseCode}' already exists. Import aborted.");
                    return redirect()->back();
                }

                $courseName = $data['course_name'];
                $teachersData = $data['teachers'];
                $studentsData = $data['students'];
                $assessmentsData = $data['assessments'];

                // Create the course if it doesn't exist
                $course = Course::create([
                    'course_code' => $courseCode,
                    'name' => $courseName,
                ]);

                // Get the logged-in teacher
                $loggedInTeacher = Auth::guard('teacher')->user();

                // Process each teacher
                foreach ($teachersData as $teacherData) {
                    $teacherSnumber = $teacherData['snumber'];
                    $teacherName = $teacherData['name'];
                    $teacherEmail = $teacherData['email'];

                    // Check if the teacher already exists by snumber or email
                    $teacher = Teacher::where('snumber', $teacherSnumber)
                        ->orWhere('email', $teacherEmail)
                        ->first();

                    if (!$teacher) {
                        // Create the teacher if they don't exist
                        $teacher = Teacher::create([
                            'name' => $teacherName,
                            'email' => $teacherEmail,
                            'snumber' => $teacherSnumber,
                            'password' => bcrypt($teacherSnumber),
                        ]);
                    }

                    // Insert into teacher_courses pivot table
                    DB::table('teacher_courses')->updateOrInsert(
                        [
                            'teacher_id' => $teacher->id,
                            'course_code' => $courseCode
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // Add the logged-in teacher to the course if not already included
                if ($loggedInTeacher) {
                    $loggedInTeacherExists = DB::table('teacher_courses')
                        ->where('teacher_id', $loggedInTeacher->id)
                        ->where('course_code', $courseCode)
                        ->exists();

                    if (!$loggedInTeacherExists) {
                        DB::table('teacher_courses')->insert([
                            'teacher_id' => $loggedInTeacher->id,
                            'course_code' => $courseCode,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                // Process each student
                foreach ($studentsData as $studentData) {
                    $studentSnumber = $studentData['snumber'];
                    $studentName = $studentData['name'];
                    $studentEmail = $studentData['email'];

                    // Check if the student already exists by snumber or email
                    $student = Student::where('snumber', $studentSnumber)
                        ->orWhere('email', $studentEmail)
                        ->first();

                    if (!$student) {
                        // Create the student if they don't exist
                        $student = Student::create([
                            'name' => $studentName,
                            'email' => $studentEmail,
                            'snumber' => $studentSnumber,
                            'password' => bcrypt($studentSnumber),
                        ]);
                    }

                    // Insert into student_courses pivot table
                    DB::table('student_courses')->updateOrInsert(
                        [
                            'student_id' => $student->id,
                            'course_code' => $courseCode
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // Process each assessment
                foreach ($assessmentsData as $assessmentData) {
                    $assessmentTitle = $assessmentData['title'];
                    $assessmentInstruction = $assessmentData['instruction'];
                    $assessmentDueDate = $assessmentData['due_date'];
                    $assessmentDueTime = $assessmentData['due_time'];
                    $assessmentType = $assessmentData['type'];

                    // Check if the assessment already exists
                    $existingAssessment = Assessment::where('title', $assessmentTitle)
                        ->where('course_id', $course->id)
                        ->first();

                    if (!$existingAssessment) {
                        // Create assessment and associate it with the course if it doesn't already exist
                        $assessment = new Assessment([
                            'title' => $assessmentTitle,
                            'instruction' => $assessmentInstruction,
                            'num_reviews' => 3,
                            'max_score' => 100,
                            'due_date' => $assessmentDueDate,
                            'due_time' => $assessmentDueTime,
                            'type' => $assessmentType,
                            'course_id' => $course->id,
                        ]);

                        $assessment->save();
                    }
                }
            }
            session()->flash('success', 'Course data imported successfully!');
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Error importing course data', ['error' => $e->getMessage()]);
            session()->flash('error', 'An error occurred while importing the course data.');
            return redirect()->back();
        }
    }
    public function deleteCourse($courseCode)
    {
        try {
            // Find the course by course code using Eloquent
            Log::info("Attempting to find course with course_code: {$courseCode}");
            $course = Course::where('course_code', $courseCode)->firstOrFail();

            // Detach all relationships with teachers and students using Eloquent
            Log::info("Detaching all teachers and students from course: {$courseCode}");
            $course->teachers()->detach();
            $course->students()->detach();
            Log::info("Successfully detached all teachers and students.");

            // Delete the course itself using Eloquent
            Log::info("Deleting course: {$courseCode}");
            $course->delete();
            Log::info("Course deleted successfully.");

            session()->flash('success', 'Course deleted successfully.');
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Error deleting course', ['error' => $e->getMessage()]);
            session()->flash('error', 'An error occurred while deleting the course.');
            return redirect()->route('home');
        }
    }
}
