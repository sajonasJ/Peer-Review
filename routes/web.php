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
