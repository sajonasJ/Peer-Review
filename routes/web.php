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

// landing page
Route::get('/', function () {
    return view('pages.login');
})->name('login');

// middleware for unauthenticated users registering
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

//teacher logging in
Route::get('/teaching-login', function () {
    return view('pages.teaching-login');
})->name('teaching-login');

// student logging in
Route::post(
    '/student-login',
    [AuthenticatedSessionController::class, 'store']
)->name('student.login');

//teacher login
Route::post(
    '/teacher/login',
    [TeacherAuthenticatedSessionController::class, 'store']
)->name('teacher.login');

//teacher logout
Route::post(
    '/teacher/logout',
    [TeacherAuthenticatedSessionController::class, 'destroy']
)->name('teacher.logout');

//student logout
Route::post(
    '/logout',
    [AuthenticatedSessionController::class, 'destroy']
)->middleware('auth')
    ->name('logout');

//loggedin student and teacher
Route::middleware(['auth:web,teacher'])->group(function () {
    Route::get('/home', [CourseController::class, 'index'])
        ->name('home');

        //course display
    Route::get('/course-details', function () {
        return view('pages.course-details');
    })->name('course-details');

    //course show
    Route::get(
        '/course-details/{courseCode}',
        [CourseController::class, 'show']
    )->name('course-details');

    //course create assessment
    Route::get(
        '/course-details/{courseCode}/add-assessment',
        [AssessmentController::class, 'create']
    )->name('add-assessment');

    //course store assessment
    Route::post(
        '/course-details/{courseCode}/add-assessment',
        [AssessmentController::class, 'store']
    )->name('store-assessment');

    //course show assessment
    Route::get(
        '/course-details/{courseCode}/assessment-details/{assessmentId}',
        [AssessmentController::class, 'show']
    )->name('assessment-details');

    //display reviews
    Route::get(
        '/course-details/{courseCode}/add-review/{studentId}/{assessmentId}',
        [ReviewController::class, 'create']
    )->name('add-review');

    //store reviews
    Route::post(
        '/course-details/{courseCode}/add-review/{studentId}/{assessmentId}',
        [ReviewController::class, 'store']
    )->name('store-review');

    //update assessment
    Route::post(
        '/course-details/{courseCode}/assessment-details/{assessmentId}/update',
        [AssessmentController::class, 'update']
    )->name('update-assessment');

    //show assessment when editing
    Route::get(
        '/course-details/{courseCode}/assessments/{assessmentId}/edit',
        [AssessmentController::class, 'edit']
    )->name('edit-assessment');

    //enroll student
    Route::post(
        '/course-details/{courseCode}/enroll-student/{studentId}',
        [CourseController::class, 'enrollStudent']
    )->name('enroll-student');

    //import data path
    Route::post(
        '/import-course-data',
        [CourseController::class, 'importCourseData']
    )->name('import-course-data');

    //delete course
    Route::delete(
        '/delete-course/{courseCode}',
        [CourseController::class, 'deleteCourse']
    )->name('delete-course');

    //assign reviewee - not working
    Route::post(
        '/course-details/{courseCode}/assessment-details/{assessmentId}/assign-reviewee',
        [AssessmentController::class, 'assignReviewee']
    )->name('assign-reviewee');

    //assign reviewer - not working
    Route::post(
        '/course-details/{courseCode}/assessment-details/{assessmentId}/assign-reviewer',
        [AssessmentController::class, 'assignReviewer']
    )->name('assign-reviewer');

    //view assessment path for students
    Route::get(
        '/course-details/{courseCode}/assessment/{assessmentId}/view',
        [AssessmentController::class, 'viewAssessment']
    )->name('view-assessment');
});


require __DIR__ . '/auth.php';
