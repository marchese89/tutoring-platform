<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegistrazioneController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordResetController;

Route::view('login', 'sicurezza.login')->name('login');

Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LogoutController::class, 'logout']);

Route::view('register', 'sicurezza.registrati')->name('register');
Route::post('register', [RegistrazioneController::class, 'carica_utente'])->name('register.store');

Route::view('password/forgot', 'public.recupera-password')->name('password.request');
Route::post('password/email', [LoginController::class, 'recupera_password'])->name('password.email');
Route::get('password/reset/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
Route::post('password/reset', [PasswordResetController::class, 'update'])->name('password.update');

// Legacy Italian auth routes. Keep these until Blade forms and redirects move to named English routes.
Route::post('registrati', [RegistrazioneController::class, 'carica_utente']);
Route::post('recupera-password', [LoginController::class, 'recupera_password']);
Route::view('recupera-password', 'public.recupera-password');
Route::get('reset-password/{token}', [PasswordResetController::class, 'edit']);
Route::post('reset-password', [PasswordResetController::class, 'update']);
