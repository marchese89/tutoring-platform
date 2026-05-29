<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\LessonOnRequestController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\Admin\MatterController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\ExerciseController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('registration/success', 'sicurezza.registrazione_ok')->name('registration.success');
Route::view('registration/error', 'sicurezza.registrazione_no')->name('registration.error');

Route::view('privacy-policy', 'public.privacy-policy')->name('privacy-policy');
Route::view('cookie-policy', 'public.coockie-policy')->name('cookie-policy');
Route::view('about', 'public.informazioni')->name('about');

Route::get('theme-areas', [ThemeAreaController::class, 'publicIndex'])->name('theme-areas.index');
Route::get('subjects/{id_at}', [MatterController::class, 'publicIndex'])->name('subjects.index');
Route::get('courses/{id_materia}', [CourseController::class, 'publicIndex'])->name('courses.index');
Route::get('courses/{id}', [CourseController::class, 'show'])->name('courses.show');

Route::get('lessons/{id_lezione}/courses/{id_corso}/presentation', [LessonController::class, 'viewPresentation'])
    ->name('lessons.presentation');
Route::get('lessons/{id_lezione}/courses/{id_corso}', [LessonController::class, 'view'])
    ->name('lessons.show');
Route::get('exercises/{id_esercizio}/courses/{id_corso}/trace', [ExerciseController::class, 'viewTrace'])
    ->name('exercises.trace');

Route::view('lesson-requests/create', 'public.lezione-su-richiesta')->name('lesson-requests.create');
Route::post('lesson-requests/files', [LessonOnRequestController::class, 'add_file_su_richiesta'])
    ->name('lesson-requests.files.store');
Route::delete('lesson-requests/files', [LessonOnRequestController::class, 'elimina_lez_rich'])
    ->name('lesson-requests.files.destroy');
Route::post('lesson-requests', [LessonOnRequestController::class, 'carica_lez_rich'])
    ->name('lesson-requests.store');
Route::view('lesson-requests/success', 'public.esito-lez-rich')->name('lesson-requests.success');

// Legacy Italian public routes. Keep names used by current Blade files and redirects.
Route::view('registrati', 'sicurezza.registrati');
Route::view('registrazione_ok', 'sicurezza.registrazione_ok')->name('registrazione_ok');
Route::view('registrazione_no', 'sicurezza.registrazione_no')->name('registrazione_no');
Route::view('privacy', 'public.privacy-policy');
Route::view('coockie', 'public.coockie-policy');
Route::view('informazioni', 'public.informazioni');
Route::get('aree-tematiche', [ThemeAreaController::class, 'publicIndex'])->name('aree-tematiche');
Route::get('materie/{id_at}', [MatterController::class, 'publicIndex'])->name('materie');
Route::get('corsi/{id_materia}', [CourseController::class, 'publicIndex'])->name('corsi');
Route::get('corso/{id}', [CourseController::class, 'show'])->name('corso');
Route::get('presentazione-lezione/{id_lezione}/{id_corso}', [LessonController::class, 'viewPresentation'])
    ->name('presentazione-lezione');
Route::get('visualizza-lezione/{id_lezione}/{id_corso}', [LessonController::class, 'view'])
    ->name('visualizza-lezione');
Route::get('traccia-esercizio/{id_esercizio}/{id_corso}', [ExerciseController::class, 'viewTrace'])
    ->name('traccia-esercizio');
Route::view('lezione-su-richiesta', 'public.lezione-su-richiesta')->name('lezione-su-richiesta');
Route::post('add-file-su-richiesta', [LessonOnRequestController::class, 'add_file_su_richiesta']);
Route::get('elimina-lez-rich', [LessonOnRequestController::class, 'elimina_lez_rich']);
Route::post('carica-lez-rich', [LessonOnRequestController::class, 'carica_lez_rich']);
Route::view('esito-lez-rich', 'public.esito-lez-rich')->name('esito-lez-rich');
