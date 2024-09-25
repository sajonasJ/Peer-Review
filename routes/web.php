<?php
require_once app_path('Includes/defs.php');
require_once app_path('Includes/functionRoutes.php');

use Illuminate\Support\Facades\Route;


// Login route
Route::get('/', function () {
    return view('pages.login');
})->name('login');

// Registration route
Route::get('/register', function () {
    return view('pages.register');
})->name('register');

Route::get('/teaching-login', function () {
    return view('pages.teaching-login');
})->name('teaching-login');


Route::get('/course-details', function () {
    return view('pages.course-details');
})->name('course-details');

Route::get('/home', function () {
    return view('pages.home');
})->name('home');

Route::get('/add-assessment', function () {
    return view('pages.add-assessment');
})->name('add-assessment');

Route::get('/add-review', function () {
    return view('pages.add-review');
})->name('add-review');