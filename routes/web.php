<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\TeacherAuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CourseController;

Route::get('/', function () {
    return view('pages.login');
})->name('login');

// Registration routes grouped with 'guest' middleware
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::get('/teaching-login', function () {
    return view('pages.teaching-login');
})->name('teaching-login');

Route::post('/student-login', [AuthenticatedSessionController::class, 'store'])->name('student.login');

// Handle teacher login form submission
Route::post('/teacher/login', [TeacherAuthenticatedSessionController::class, 'store'])->name('teacher.login');

// Handle teacher logout
Route::post('/teacher/logout', [TeacherAuthenticatedSessionController::class, 'destroy'])->name('teacher.logout');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth:web,teacher'])->group(function () {
    Route::get('/home', [CourseController::class, 'index'])->name('home');


    Route::get('/course-details', function () {
        return view('pages.course-details');
    })->name('course-details');
    Route::get('/course-details/{courseCode}', [CourseController::class, 'show'])->name('course-details');



    Route::get('/add-assessment', function () {
        return view('pages.add-assessment');
    })->name('add-assessment');

    Route::get('/add-review', function () {
        return view('pages.add-review');
    })->name('add-review');
});

require __DIR__ . '/auth.php';
