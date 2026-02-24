<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\user\UserDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
});

Route::get('/login', [AuthController::class , 'login'])->name('login');
Route::get('/register', [AuthController::class , 'register'])->name('register');
Route::post('/login', [AuthController::class , 'handleLogin'])->name('login.handle');
Route::post('/register', [AuthController::class , 'handleRegister'])->name('register.handle');
Route::post('/logout', [AuthController::class , 'logout'])->name('logout');
Route::get('/user/dashboard', [UserDashboardController::class , 'index'])->name('user.dashboard')->middleware('isUser');
Route::get('/admin/dashboard', [AdminDashboardController::class , 'index'])->name('admin.dashboard')->middleware('isAdmin');
Route::get('/home', [HomeController::class , 'index'])->name('home');
Route::resource('colocations', CollocationsController::class);
Route::get('invitations', [InvitationController::class , 'accept'])->name('invitations.accept');