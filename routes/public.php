<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\LessonRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('registration/success', 'sicurezza.registrazione_ok')->name('registration.success');
Route::view('registration/error', 'sicurezza.registrazione_no')->name('registration.error');

Route::view('privacy-policy', 'public.privacy-policy')->name('privacy-policy');
Route::view('cookie-policy', 'public.coockie-policy')->name('cookie-policy');
Route::view('about', 'public.informazioni')->name('about');

Route::get('theme-areas', [ThemeAreaController::class, 'publicIndex'])->name('theme-areas.index');
Route::get('theme-areas/{id_at}/subjects', [SubjectController::class, 'publicIndex'])->name('subjects.index');
Route::get('subjects/{id_materia}/courses', [CourseController::class, 'publicIndex'])->name('courses.index');
Route::get('courses/{id}', [CourseController::class, 'show'])->name('courses.show');

Route::get('courses/{id_corso}/lessons/{id_lezione}/presentation', [LessonController::class, 'viewPresentation'])
    ->name('lessons.presentation');
Route::get('courses/{id_corso}/lessons/{id_lezione}', [LessonController::class, 'view'])
    ->name('lessons.show');
Route::get('courses/{id_corso}/exercises/{id_esercizio}/trace', [ExerciseController::class, 'viewTrace'])
    ->name('exercises.trace');

Route::view('lesson-requests/create', 'public.lezione-su-richiesta')->name('lesson-requests.create');
Route::post('lesson-requests/files', [LessonRequestController::class, 'storeRequestFile'])
    ->name('lesson-requests.files.store');
Route::delete('lesson-requests/files', [LessonRequestController::class, 'destroyRequestFile'])
    ->name('lesson-requests.files.destroy');
Route::post('lesson-requests', [LessonRequestController::class, 'store'])
    ->name('lesson-requests.store');
Route::view('lesson-requests/success', 'public.esito-lez-rich')->name('lesson-requests.success');
