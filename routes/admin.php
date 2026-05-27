<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ModDatiAdminController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\Admin\MatterController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\AcquistiController;
use App\Http\Controllers\admin\BillingController;
use App\Http\Controllers\Public\LessonOnRequestController;
use App\Http\Controllers\InvoiceController;

Route::middleware(['auth', 'role:admin'])->group(function () {

    // =====================================================
    // 🧩 UI / PAGINE ADMIN (SOLO VIEW)
    // =====================================================
    Route::get('dashboard-admin', fn() => view('layouts.dashboard-admin'))->name('dashboard-admin');
    Route::get('imp-account', fn() => view('admin.settings.imp-account'))->name('imp-account');
    Route::get('mod-dati-pers', fn() => view('admin.settings.mod-dati-pers'))->name('mod-dati-pers');
    Route::get('mod-cred', fn() => view('admin.settings.mod-cred'))->name('mod-cred');
    Route::get('mod-foto-admin', fn() => view('admin.settings.mod-foto'))->name('mod-foto-admin');
    Route::get('mod-indirizzo-admin', fn() => view('admin.settings.mod-indirizzo'))->name('mod-indirizzo-admin');
    Route::get('mod-certif', fn() => view('admin.settings.mod-certif'))->name('mod-certif');
    Route::get('aggiungi-certif', fn() => view('admin.settings.add-certif'))->name('aggiungi-certif');
    Route::get('insegnamento', fn() => view('admin.teaching.insegnamento'))->name('insegnamento');
    Route::get('nuovo-corso', [CourseController::class, 'index'])->name('nuovo-corso');
    Route::get('aree-tem', [ThemeAreaController::class, 'index'])
        ->name('aree-tem');
    Route::get('materie', [MatterController::class, 'index'])
        ->name('admin.teaching.materie');
    Route::get('elenco-corsi', [CourseController::class, 'list'])
        ->name('elenco-corsi');

    // =====================================================
    // 👤 ACCOUNT / PROFILO ADMIN
    // =====================================================
    Route::post('mod-indirizzo-admin', [ModDatiAdminController::class, 'mod_ind']);
    Route::post('upload-foto-admin', [ModDatiAdminController::class, 'upload_foto']);
    Route::post('mod-foto-cert-admin', [ModDatiAdminController::class, 'upload_cert']);
    Route::post('crea-foto-cert-admin', [ModDatiAdminController::class, 'upload_cert_session']);
    Route::post('mod-nome-cert-admin', [ModDatiAdminController::class, 'modifica_nome_cert']);
    Route::post('elimina_certificato', [ModDatiAdminController::class, 'elimina_cert']);
    Route::get('del_cert_admin', [ModDatiAdminController::class, 'elimina_cert_session']);
    Route::post('add-cert-admin', [ModDatiAdminController::class, 'add_cert_admin']);
    Route::post('mod-email-admin', [ModDatiAdminController::class, 'mod_email_admin']);
    Route::post('mod-pass-admin', [ModDatiAdminController::class, 'mod_pass_admin']);
    Route::post('mod-piva', [ModDatiAdminController::class, 'mod_piva']);
    Route::get('mod-part-iva', fn() => view('admin.settings.mod-part-iva'))->name('mod-part-iva');

    // =====================================================
    // 📚 CORSI / MATERIE / AREE TEMATICHE
    // =====================================================
    Route::post('theme-areas', [ThemeAreaController::class, 'store']);
    Route::put('theme-areas/{id}', [ThemeAreaController::class, 'update']);
    Route::delete('theme-areas/{id}', [ThemeAreaController::class, 'destroy']);

    Route::post('matter', [MatterController::class, 'store']);
    Route::put('matter/{id}', [MatterController::class, 'update']);
    Route::delete('matter/{id}', [MatterController::class, 'destroy']);

    Route::post('courses', [CourseController::class, 'store']);
    Route::put('courses/{id}', [CourseController::class, 'update']);
    Route::delete('courses/{id}', [CourseController::class, 'destroy']);

    Route::get('modifica-dettagli-corso/{id}', [CourseController::class, 'edit'])->name('modifica-dettagli-corso');

    // ===============================
    // 🎓 LEZIONI
    // ===============================

    // UI
    Route::get('nuova-lezione/{id}', [LessonController::class, 'create'])->name('nuova-lezione');
    Route::post('carica-lezione', [LessonController::class, 'store']);
    Route::get('modifica-lezione/{id_corso}/{id_lezione}', [fn($id_corso, $id_lezione) => view('admin.teaching.modifica-lezione', compact('id_corso', 'id_lezione'))])->name('modifica-lezione');

    // Upload temporanei (sessione)
    Route::post('lessons/upload-presentation', [LessonController::class, 'uploadPresentation']);
    Route::delete('lessons/upload-presentation', [LessonController::class, 'deletePresentationSession']);

    Route::post('lessons/upload-file', [LessonController::class, 'uploadLessonFile']);
    Route::delete('lessons/upload-file', [LessonController::class, 'deleteLessonSession']);

    // CRUD
    Route::post('lessons', [LessonController::class, 'store']);
    Route::put('lessons/{id}', [LessonController::class, 'update']);
    Route::delete('lessons/{id}', [LessonController::class, 'destroy']);

    // Update file definitivi
    Route::post('lessons/{id}/presentation', [LessonController::class, 'updatePresentation']);
    Route::post('lessons/{id}/file', [LessonController::class, 'updateLessonFile']);


    // =====================================================
    // 🧪 ESERCIZI
    // =====================================================

    // view
    Route::get('exercises/create/{course}', fn() => view('admin.teaching.nuovo-esercizio'))->name('nuovo-esercizio');

    Route::get('exercises/{course}/edit/{exercise}', fn() => view('admin.teaching.modifica-esercizio'))->name('modifica-esercizio');


    // =========================
    // UPLOAD TEMP FILE (SESSION)
    // =========================
    Route::post('exercises/trace/upload', [ExerciseController::class, 'uploadTrace']);
    Route::post('exercises/execution/upload', [ExerciseController::class, 'uploadExecution']);

    Route::delete('exercises/trace/session', [ExerciseController::class, 'clearTraceSession']);
    Route::delete('exercises/execution/session', [ExerciseController::class, 'clearExecutionSession']);


    // =========================
    // CRUD ESERCIZI
    // =========================
    Route::post('exercises', [ExerciseController::class, 'store']);
    Route::put('exercises/{id}', [ExerciseController::class, 'update']);
    Route::delete('exercises/{id}', [ExerciseController::class, 'destroy']);


    // =========================
    // UPDATE FILE SINGOLI
    // =========================
    Route::post('exercises/{id}/trace', [ExerciseController::class, 'updateTrace']);
    Route::post('exercises/{id}/execution', [ExerciseController::class, 'updateExecution']);

    // =====================================================
    // 👥 STUDENTI / RICHIESTE / VENDITE
    // =====================================================
    Route::get('studenti', fn() => view('admin.students.studenti'))->name('studenti');
    Route::get('richieste-studenti', [LessonOnRequestController::class, 'index'])->name('richieste-studenti');
    Route::get('visualizza-richiesta/{id}', [LessonOnRequestController::class, 'visualizzaRichiesta'])->name('visualizza-richiesta');
    Route::post('sol-rich-upload', [LessonOnRequestController::class, 'sol_rich_upload']);
    Route::delete('lez-rich-rem-exec-{id}', [LessonOnRequestController::class, 'lez_rich_rem_exec'])->name('lez-rich-rem-exec');
    Route::post('carica-prezzo-lez-rich', [LessonOnRequestController::class, 'carica_prezzo_lez_rich']);

    Route::get('vendite', [BillingController::class, 'vendite'])->name('vendite');
    Route::get('/cambia_tabella_ordini', [BillingController::class, 'cambiaTabellaOrdini']);
    Route::get('admin-ordine-{id}', [BillingController::class, 'showOrder'])->name('admin-ordine');
    Route::get('admin-fattura-{id}', [BillingController::class, 'showInvoice'])->name('admin-fattura');

    Route::post('crea_fattura_extra', [AcquistiController::class, 'crea_fattura']);
    Route::get('extra-fattura', fn() => view('admin.billing.fattura-extra'))->name('extra-fattura');
    Route::get('fattura-creata', fn() => view('admin.billing.fattura-creata'))->name('fattura-creata');
    Route::get('fatture', [InvoiceController::class, 'showAll'])->name('fatture');
    Route::get('visualizza-fattura/{id}', [InvoiceController::class, 'show'])->name('visualizza-fattura');

    // =====================================================
    // 💬 CHAT / AJAX
    // =====================================================
    Route::get('chat-studenti', [LessonOnRequestController::class, 'chatStudenti'])->name('chat-studenti');
    Route::get('visualizza-chat-{id}', [LessonOnRequestController::class, 'visualizzaChat'])->name('visualizza-chat');

    Route::get('cambia_tabella_ordini', [AjaxController::class, 'getOrdini']);
    Route::post('chat/admin/invia-messaggio', [AjaxController::class, 'invia_messaggio']);
    Route::get('leggi-messaggi-insegnante-{id_chat}', [AjaxController::class, 'leggi_messaggi']);
});
