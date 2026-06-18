<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\Public\LessonRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('locale', [LocaleController::class, 'update'])->name('locale.update');

Route::view('registration/success', 'auth.registration-success')->name('registration.success');
Route::view('registration/error', 'auth.registration-error')->name('registration.error');

Route::view('privacy-policy', 'public.privacy-policy')->name('privacy-policy');
Route::view('cookie-policy', 'public.cookie-policy')->name('cookie-policy');
Route::get('about', [HomeController::class, 'about'])->name('about');

Route::get('theme-areas', [ThemeAreaController::class, 'publicIndex'])->name('theme-areas.index');
Route::get('theme-areas/{themeArea}/subjects', [SubjectController::class, 'publicIndex'])->name('subjects.index');
Route::get('subjects/{subject}/courses', [CourseController::class, 'publicIndex'])->name('courses.index');
Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

Route::get('courses/{course}/lessons/{lesson}/presentation', [LessonController::class, 'viewPresentation'])
    ->scopeBindings()
    ->name('lessons.presentation');
Route::get('courses/{course}/lessons/{lesson}', [LessonController::class, 'view'])
    ->scopeBindings()
    ->name('lessons.show');
Route::get('courses/{course}/exercises/{exercise}/trace', [ExerciseController::class, 'viewTrace'])
    ->scopeBindings()
    ->name('exercises.trace');

Route::get('lesson-requests/create', [LessonRequestController::class, 'create'])->name('lesson-requests.create');

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::post('lesson-requests/files', [LessonRequestController::class, 'storeRequestFile'])
        ->name('lesson-requests.files.store');
    Route::delete('lesson-requests/files', [LessonRequestController::class, 'destroyRequestFile'])
        ->name('lesson-requests.files.destroy');
    Route::post('lesson-requests', [LessonRequestController::class, 'store'])
        ->name('lesson-requests.store');
});

Route::view('lesson-requests/success', 'public.lesson-request-success')->name('lesson-requests.success');
