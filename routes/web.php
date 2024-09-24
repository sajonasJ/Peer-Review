<?php
require_once app_path('Includes/defs.php');
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});
