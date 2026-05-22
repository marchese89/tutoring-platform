<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Public\LessonOnRequestController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\Admin\MatterController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\ExerciseController;

Route::get('/', [HomeController::class, 'index']);

Route::view('registrati', 'sicurezza.registrati');
Route::view('registrazione_ok', 'sicurezza.registrazione_ok')->name('registrazione_ok');
Route::view('registrazione_no', 'sicurezza.registrazione_no')->name('registrazione_no');

Route::view('privacy', 'public.privacy-policy');
Route::view('coockie', 'public.coockie-policy');

Route::get('aree-tematiche', [ThemeAreaController::class, 'publicIndex'])->name('aree-tematiche');

Route::get('materie/{id_at}', [MatterController::class, 'publicIndex'])->name('materie');

Route::get('corsi/{id_materia}', [CourseController::class, 'publicIndex'])->name('corsi');

Route::get('corso/{id}', [CourseController::class, 'show'])->name('corso');

Route::get('presentazione-lezione/{id_lezione}/{id_corso}', [LessonController::class, 'viewPresentation'])->name('presentazione-lezione');
Route::view('visualizza-lezione/{id_lezione}/{id_corso}', 'public.contenuto-lezione');
Route::get('traccia-esercizio/{id_esercizio}/{id_corso}', [ExerciseController::class, 'viewTrace'])->name('traccia-esercizio');

Route::view('lezione-su-richiesta', 'public.lezione-su-richiesta');
Route::view('esito-lez-rich', 'public.esito-lez-rich');

Route::view('informazioni', 'public.informazioni');
Route::view('recupera-password', 'public.recupera-password');

Route::get('/reset-password/{token}', function ($token) {
    return view('sicurezza.reset-password', [
        'token' => $token
    ]);
})->name('password.reset');


Route::post('/reset-password', function (Request $request) {

    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();
        }
    );

    return $status === Password::PASSWORD_RESET
        ? redirect()->route('login')
        ->with('success', 'Password aggiornata!')
        : back()->withErrors(['email' => 'Errore nel reset password']);
})->name('password.update');

Route::get('lezione-su-richiesta', function () {
    return view('public.lezione-su-richiesta');
})->name('lezione-su-richiesta');

Route::post('add-file-su-richiesta', [LessonOnRequestController::class, 'add_file_su_richiesta']);

Route::get('elimina-lez-rich', [LessonOnRequestController::class, 'elimina_lez_rich']);

Route::post('carica-lez-rich', [LessonOnRequestController::class, 'carica_lez_rich']);

Route::get('esito-lez-rich', function () {
    return view('public.esito-lez-rich');
})->name('esito-lez-rich');
