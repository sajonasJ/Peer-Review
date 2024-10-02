<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\TeacherAuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StudentController;


Route::get('/', function () {
    return view('pages.login');
})->name('login');

// Registration routes grouped with 'guest' middleware
Route::middleware('guest')->group(function () {
    Route::get(
        '/register',
        [RegisteredUserController::class, 'create']
    )->name('register');
    Route::post(
        '/register',
        [RegisteredUserController::class, 'store']
    );
});

Route::get('/teaching-login', function () {
    return view('pages.teaching-login');
})->name('teaching-login');

Route::post(
    '/student-login',
    [AuthenticatedSessionController::class, 'store']
)->name('student.login');

// Handle teacher login form submission
Route::post(
    '/teacher/login',
    [TeacherAuthenticatedSessionController::class, 'store']
)->name('teacher.login');

// Handle teacher logout
Route::post(
    '/teacher/logout',
    [TeacherAuthenticatedSessionController::class, 'destroy']
)->name('teacher.logout');

Route::post(
    '/logout',
    [AuthenticatedSessionController::class, 'destroy']
)
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth:web,teacher'])->group(function () {
    Route::get('/home', [CourseController::class, 'index'])->name('home');


    Route::get('/course-details', function () {
        return view('pages.course-details');
    })->name('course-details');
    Route::get(
        '/course-details/{courseCode}',
        [CourseController::class, 'show']
    )->name('course-details');

    // Route to show the add assessment form
    Route::get(
        '/course-details/{courseCode}/add-assessment',
        [AssessmentController::class, 'create']
    )->name('add-assessment');

    // Route to store the new assessment
    Route::post(
        '/course-details/{courseCode}/add-assessment',
        [AssessmentController::class, 'store']
    )->name('store-assessment');

    // Route to display the details of a specific assessment
    Route::get(
        '/course-details/{courseCode}/assessment-details/{assessmentId}',
        [AssessmentController::class, 'show']
    )->name('assessment-details');

    // Route to show the add review form
    Route::get(
        '/course-details/{courseCode}/add-review/{studentId}/{assessmentId}',
        [ReviewController::class, 'create']
    )->name('add-review');

    // Route to store the new review
    Route::post(
        '/course-details/{courseCode}/add-review/{studentId}/{assessmentId}',
        [ReviewController::class, 'store']
    )->name('store-review');




    // Route to update an existing assessment (using POST instead of PUT)
    Route::post(
        '/course-details/{courseCode}/assessment-details/{assessmentId}/update',
        [AssessmentController::class, 'update']
    )->name('update-assessment');


    // Route to edit an existing assessment
    Route::get(
        '/course-details/{courseCode}/assessments/{assessmentId}/edit',
        [AssessmentController::class, 'edit']
    )->name('edit-assessment');

    Route::post('/course-details/{courseCode}/enroll-student/{studentId}', [CourseController::class, 'enrollStudent'])->name('enroll-student');

    Route::post('/import-course-data', [CourseController::class, 'importCourseData'])->name('import-course-data');
    Route::delete('/delete-course/{courseCode}', [CourseController::class, 'deleteCourse'])->name('delete-course');

    // Route to assign a reviewee to an assessment
    // Route to assign a reviewee to an assessment
    Route::post(
        '/course-details/{courseCode}/assessment-details/{assessmentId}/assign-reviewee',
        [AssessmentController::class, 'assignReviewee']
    )->name('assign-reviewee');

    // Route to assign a reviewer to an assessment
    Route::post(
        '/course-details/{courseCode}/assessment-details/{assessmentId}/assign-reviewer',
        [AssessmentController::class, 'assignReviewer']
    )->name('assign-reviewer');
});


require __DIR__ . '/auth.php';
