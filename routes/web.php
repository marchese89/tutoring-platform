<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Files\FileAccessController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =========================
// Modular routes
// =========================

require __DIR__ . '/public.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/student.php';
require __DIR__ . '/admin.php';

// =========================
// Protected files
// =========================

Route::get('/protected-files/{path}', FileAccessController::class)
    ->name('protected-files.show')
    ->where('path', '.*');
