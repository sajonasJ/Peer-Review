<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\TeacherAuthenticatedSessionController;


Route::get('/', function () {
    return view('pages.login');
})->name('login');

// Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Registration route
Route::get('/register', function () {
    return view('pages.register');
})->name('register');

Route::get('/teaching-login', function () {
    return view('pages.teaching-login');
})->name('teaching-login');

// Handle teacher login form submission
Route::post('/teacher/login', [TeacherAuthenticatedSessionController::class, 'store'])->name('teacher.login');

// Handle teacher logout
Route::post('/teacher/logout', [TeacherAuthenticatedSessionController::class, 'destroy'])->name('teacher.logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', function () {
        return view('pages.home');
    })->name('home');

    Route::get('/course-details', function () {
        return view('pages.course-details');
    })->name('course-details');

    Route::get('/add-assessment', function () {
        return view('pages.add-assessment');
    })->name('add-assessment');

    Route::get('/add-review', function () {
        return view('pages.add-review');
    })->name('add-review');
});


require __DIR__.'/auth.php';
