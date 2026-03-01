<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalenceController;
use App\Http\Controllers\CollocationsController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\user\UserDashboardController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class , 'login'])->name('login')->middleware('guest');
Route::get('/register', [AuthController::class , 'register'])->name('register')->middleware('guest');
Route::post('/login', [AuthController::class , 'handleLogin'])->name('login.handle')->middleware('guest');
Route::post('/register', [AuthController::class , 'handleRegister'])->name('register.handle')->middleware('guest');

Route::post('/logout', [AuthController::class , 'logout'])->name('logout')->middleware('auth');
Route::get('/profile', [UserDashboardController::class , 'profile'])->name('profile')->middleware('auth');

Route::get('/admin/dashboard', [AdminDashboardController::class , 'index'])->name('admin.dashboard')->middleware(['auth', 'isAdmin']);
Route::post('/admin/users/{user}/toggle-ban', [AdminDashboardController::class , 'toggleBan'])->name('admin.users.toggle_ban')->middleware(['auth', 'isAdmin']);

Route::get('/user/dashboard', [UserDashboardController::class , 'index'])->name('user.dashboard')->middleware(['auth', 'isUser']);

Route::get('/colocations', [CollocationsController::class , 'index'])->name('colocations.index')->middleware(['auth', 'isUser']);
Route::get('/colocations/create', [CollocationsController::class , 'create'])->name('colocations.create')->middleware(['auth', 'isUser']);
Route::post('/colocations', [CollocationsController::class , 'store'])->name('colocations.store')->middleware(['auth', 'isUser']);

Route::get('/colocations/{colocation}', [CollocationsController::class , 'show'])->name('colocations.show')->middleware(['auth', 'isUser', 'isMember']);
Route::post('/colocations/{colocation_id}/leave', [CollocationsController::class , 'leave'])->name('colocations.leave')->middleware(['auth', 'isUser', 'isMember']);

Route::get('/colocations/{colocation}/expenses/create', [ExpensesController::class , 'create'])->name('expenses.create')->middleware(['auth', 'isUser', 'isMember']);
Route::post('/colocations/{colocation}/expenses', [ExpensesController::class , 'store'])->name('expenses.store')->middleware(['auth', 'isUser', 'isMember']);

Route::get('/colocations/{colocation}/balances', [BalenceController::class , 'index'])->name('balances.index')->middleware(['auth', 'isUser', 'isMember']);
Route::post('/colocations/{colocation}/balances', [BalenceController::class , 'store'])->name('balances.store')->middleware(['auth', 'isUser', 'isMember']);

Route::post('invitations', [InvitationController::class , 'store'])->name('invitations.store')->middleware(['auth', 'isUser']);
Route::post('invitations/accept', [InvitationController::class , 'accept'])->name('invitations.accept')->middleware(['auth', 'isUser']);
Route::post('invitations/decline', [InvitationController::class , 'decline'])->name('invitations.decline')->middleware(['auth', 'isUser']);


Route::get('/colocations/{id}/settings', [CollocationsController::class , 'settings'])->name('colocations.settings')->middleware(['auth', 'isUser', 'isOwner']);
Route::post('/colocations/{id}/category-settings', [CollocationsController::class , 'category_settings'])->name('colocations.category_settings')->middleware(['auth', 'isUser', 'isOwner']);
Route::get('/colocations/{colocation}/edit', [CollocationsController::class , 'edit'])->name('colocations.edit')->middleware(['auth', 'isUser', 'isOwner']);
Route::put('/colocations/{colocation}', [CollocationsController::class , 'update'])->name('colocations.update')->middleware(['auth', 'isUser', 'isOwner']);
Route::delete('/colocations/{colocation}', [CollocationsController::class , 'destroy'])->name('colocations.destroy')->middleware(['auth', 'isUser', 'isOwner']);
Route::post('/colocations/{colocation_id}/transfer-ownership/{member_id}', [CollocationsController::class , 'transfer_ownership'])->name('colocations.transfer_ownership')->middleware(['auth', 'isUser', 'isOwner']);
Route::post('/colocations/{colocation_id}/cancel', [CollocationsController::class , 'destroy'])->name('colocations.cancel')->middleware(['auth', 'isUser', 'isOwner']);

Route::post('/colocations/{colocation_id}/remove-member/{member_id}', [CollocationsController::class , 'remove_member'])->name('colocations.remove_member')->middleware(['auth', 'isOwner']);


//DtRj1F7NsCVZMkQo56AKuccrtrnqVd9A omar
//FKpewcmspJEn7ko7CfpukOzFjNZkAXdb houssam