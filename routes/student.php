<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AcquistiController;
use App\Http\Controllers\Student\StudenteController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\CourseController;

Route::middleware(['auth', 'role:student'])->group(function () {

    // =========================
    // DASHBOARD
    // =========================
    Route::view('dashboard-studente', 'layouts.dashboard-studente')->name('dashboard-studente');

    // =========================
    // ACCOUNT (MANCAVA LA COMPATIBILITÀ VECCHIA)
    // =========================
    Route::view('imp-account-studente', 'studente.impostazioni-account')->name('imp-account-studente');
    Route::view('account', 'studente.impostazioni-account')->name('account');

    Route::view('mod-dati-pers-stud', 'studente.mod-dati-pers')->name('mod-dati-pers-stud');
    Route::view('mod-cred-stud', 'studente.mod-cred')->name('mod-cred-stud');

    Route::post('mod-indirizzo-stud', [StudenteController::class, 'mod_indirizzo_stud']);
    Route::post('mod-email-stud', [StudenteController::class, 'mod_email_stud']);
    Route::post('mod-pass-stud', [StudenteController::class, 'mod_pass_stud']);

    // =========================
    // CORSI
    // =========================
    Route::get('corsi', [CourseController::class, 'mieiCorsi'])->name('studente.corsi');
    Route::view('course/{id}', 'studente.corso')->name('course');

    Route::view('studente/corso/{id}', 'studente.corso')->name('studente.corso');

    Route::get('lezione/{id_corso}/{id_lezione}', [StudenteController::class, 'lezione'])->name('lezione');
    Route::get('esercizio/{id_corso}/{id_esercizio}', [StudenteController::class, 'esercizio'])->name('esercizio');

    // =========================
    // CARRELLO (NUOVO + VECCHIO COMPATIBILE)
    // =========================
    Route::view('carrello', 'public.visualizza-carrello')->name('carrello');

    Route::get('carrello/add/{id}/{type}', [AcquistiController::class, 'aggiungi_al_carrello']);

    Route::delete('carrello/remove/{id}/{type}', [AcquistiController::class, 'rimuovi_dal_carrello']);

    // =========================
    // CHECKOUT / PAGAMENTI
    // =========================
    Route::view('checkout', 'public.acquista');

    Route::post('prepara-pagamento', [AcquistiController::class, 'prepara_pagamento']);
    Route::post('/payment/process', [AcquistiController::class, 'process_payment']);

    Route::get('processa_pagamento', [AcquistiController::class, 'processa_pagamento']);
    Route::get('payment/success', [AcquistiController::class, 'processa_acquisto']);

    Route::get('acquisto-effettuato', [AcquistiController::class, 'processa_acquisto']);
    Route::get('acquisto-a-buon-fine', function () {
        return view('public.acquisto-effettuato');
    });

    Route::get('paga', function () {
        return view('studente.paga');
    });

    Route::get('pagamento-ok', function () {
        return view('studente.pagamento-ok');
    });

    Route::view('payment/extra', 'studente.pagamento-extra')->name('extra-payment');

    // =========================
    // ORDINI / FATTURE (VECCHIE RIPRISTINATE)
    // =========================
    Route::view('ordini', 'studente.ordini')->name('ordini');
    Route::view('ordine-{id}', 'studente.ordine');

    Route::view('fatture-studente', 'studente.fatture-studente')->name('fatture-studente');
    Route::view('fattura-{id}', 'studente.fattura');

    Route::view('fattura0-studente-{id}', 'studente.fattura-studente')->name('fattura0-studente');

    // =========================
    // RICHIESTE DIRETTE
    // =========================
    Route::view('richieste-dirette', 'studente.richieste-dirette')->name('richieste-dirette');
    Route::view('richieste-dirette-acquistate', 'studente.richieste-dirette-acquistate')->name('richieste-dirette-aquistate');
    Route::get('visualizza-richiesta-studente/{id}', function ($id) {
        return view('studente.visualizza-richiesta-lezione', compact('id'));
    })->name('visualizza-richiesta-studente');

    // =========================
    // CHAT
    // =========================

    Route::post('chat/studente/invia-messaggio', [AjaxController::class, 'invia_messaggio']);

    // =========================
    // FEEDBACK / RECENSIONI
    // =========================
    Route::view('recensione', 'studente.recensione')->name('recensione');

    Route::get('invia-feedback-{punteggio}', [AjaxController::class, 'invia_feedback']);
    Route::get('invia-recensione-{testo}', [AjaxController::class, 'invia_recensione']);

    // =========================
    // AJAX VARI
    // =========================
    Route::get('cambia_tabella_ordini_studente', [AjaxController::class, 'getOrdini']);
});
