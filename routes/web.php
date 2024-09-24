<?php
require_once app_path('Includes/defs.php');
require_once app_path('Includes/functionRoutes.php');

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.login');
});
