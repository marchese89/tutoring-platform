<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::view('login', 'auth.login')->name('login');

Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LogoutController::class, 'logout'])->name('logout');

Route::view('register', 'auth.register')->name('register');
Route::post('register', [RegistrationController::class, 'store'])->name('register.store');

Route::view('password/forgot', 'auth.forgot-password')->name('password.request');
Route::post('password/email', [LoginController::class, 'sendPasswordResetLink'])->name('password.email');
Route::get('password/reset/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
Route::post('password/reset', [PasswordResetController::class, 'update'])->name('password.update');
