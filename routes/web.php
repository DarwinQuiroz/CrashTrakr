<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/login', [LoginController::class, 'index'])->name('login');

Route::get('/auth/register', [RegisterController::class, 'index'])->name('register');