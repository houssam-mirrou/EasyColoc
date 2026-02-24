<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/login', [AuthController::class , 'login'])->name('login');
Route::get('/register', [AuthController::class , 'register'])->name('register');

Route::post('/login', [AuthController::class , 'handleLogin'])->name('login.handle');
Route::post('/register', [AuthController::class , 'handleRegister'])->name('register.handle');

Route::post('/logout', [AuthController::class , 'logout'])->name('logout');