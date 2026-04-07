<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes are split into focused files:
|   shared.php    - admin management, courses, academic years, profile
|   goodmoral.php - all Good Moral Application workflow routes (per role)
|   auth.php      - authentication (login, register, password reset)
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

require __DIR__ . '/shared.php';
require __DIR__ . '/goodmoral.php';
require __DIR__ . '/auth.php';