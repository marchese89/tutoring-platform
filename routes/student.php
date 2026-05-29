<?php

use App\Http\Controllers\AcquistiController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Student\RouteController;
use App\Http\Controllers\Student\StudenteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->group(function () {
    // =========================
    // Student dashboard
    // =========================
    Route::view('student/dashboard', 'layouts.dashboard-studente')->name('student.dashboard');

    // =========================
    // Student account
    // =========================
    Route::view('student/account', 'studente.impostazioni-account')->name('student.account');
    Route::view('student/account/profile', 'studente.mod-dati-pers')->name('student.account.profile');
    Route::view('student/account/credentials', 'studente.mod-cred')->name('student.account.credentials');

    Route::post('student/account/address', [StudenteController::class, 'mod_indirizzo_stud'])
        ->name('student.account.address.update');
    Route::post('student/account/email', [StudenteController::class, 'mod_email_stud'])
        ->name('student.account.email.update');
    Route::post('student/account/password', [StudenteController::class, 'mod_pass_stud'])
        ->name('student.account.password.update');

    // =========================
    // Student courses
    // =========================
    Route::get('student/courses', [CourseController::class, 'mieiCorsi'])->name('student.courses.index');
    Route::get('student/courses/{id}', [RouteController::class, 'show'])->name('student.courses.show');
    Route::get('student/courses/{id_corso}/lessons/{id_lezione}', [StudenteController::class, 'lezione'])
        ->name('student.lessons.show');
    Route::get('student/courses/{id_corso}/exercises/{id_esercizio}', [StudenteController::class, 'esercizio'])
        ->name('student.exercises.show');

    // =========================
    // Cart and checkout
    // =========================
    Route::view('cart', 'public.visualizza-carrello')->name('cart.show');
    Route::get('cart/items/{id}/{type}', [AcquistiController::class, 'aggiungi_al_carrello'])
        ->name('cart.items.store');
    Route::delete('cart/items/{id}/{type}', [AcquistiController::class, 'rimuovi_dal_carrello'])
        ->name('cart.items.destroy');

    Route::view('checkout', 'public.acquista')->name('checkout.show');
    Route::post('checkout/payment', [AcquistiController::class, 'prepara_pagamento'])
        ->name('checkout.payment.prepare');
    Route::post('payment/process', [AcquistiController::class, 'process_payment'])
        ->name('payment.process');
    Route::get('payment/process', [AcquistiController::class, 'processa_pagamento'])
        ->name('payment.process.legacy');
    Route::get('payment/success', [AcquistiController::class, 'processa_acquisto'])
        ->name('payment.success');
    Route::view('payment/complete', 'public.acquisto-effettuato')->name('payment.complete');
    Route::view('payment/pay', 'studente.paga')->name('payment.pay');
    Route::view('payment/ok', 'studente.pagamento-ok')->name('payment.ok');
    Route::view('payment/extra', 'studente.pagamento-extra')->name('payment.extra');

    // =========================
    // Orders and invoices
    // =========================
    Route::view('student/orders', 'studente.ordini')->name('student.orders.index');
    Route::view('student/orders/{id}', 'studente.ordine')->name('student.orders.show');
    Route::view('student/invoices', 'studente.fatture-studente')->name('student.invoices.index');
    Route::view('student/invoices/{id}', 'studente.fattura')->name('student.invoices.show');
    Route::view('student/invoices/{id}/legacy', 'studente.fattura-studente')->name('student.invoices.legacy-show');

    // =========================
    // Direct requests
    // =========================
    Route::view('student/direct-requests', 'studente.richieste-dirette')->name('student.direct-requests.index');
    Route::view('student/direct-requests/purchased', 'studente.richieste-dirette-acquistate')
        ->name('student.direct-requests.purchased');
    Route::get('student/direct-requests/{id}', function ($id) {
        return view('studente.visualizza-richiesta-lezione', compact('id'));
    })->name('student.direct-requests.show');

    // =========================
    // Chat, feedback and partial data
    // =========================
    Route::post('student/chat/messages', [AjaxController::class, 'invia_messaggio'])
        ->name('student.chat.messages.store');
    Route::view('student/review', 'studente.recensione')->name('student.review');
    Route::post('student/feedback/{punteggio}', [AjaxController::class, 'invia_feedback'])
        ->name('student.feedback.store');
    Route::post('student/review/{testo}', [AjaxController::class, 'invia_recensione'])
        ->name('student.review.store');
    Route::get('student/orders-table', [AjaxController::class, 'getOrdini'])
        ->name('student.orders.table');

    // =========================
    // Legacy Italian routes
    // =========================
    Route::view('dashboard-studente', 'layouts.dashboard-studente')->name('dashboard-studente');

    Route::view('imp-account-studente', 'studente.impostazioni-account')->name('imp-account-studente');
    Route::view('account', 'studente.impostazioni-account')->name('account');

    Route::view('mod-dati-pers-stud', 'studente.mod-dati-pers')->name('mod-dati-pers-stud');
    Route::view('mod-cred-stud', 'studente.mod-cred')->name('mod-cred-stud');

    Route::post('mod-indirizzo-stud', [StudenteController::class, 'mod_indirizzo_stud']);
    Route::post('mod-email-stud', [StudenteController::class, 'mod_email_stud']);
    Route::post('mod-pass-stud', [StudenteController::class, 'mod_pass_stud']);

    Route::get('corsi', [CourseController::class, 'mieiCorsi'])->name('studente.corsi');
    Route::get('course/{id}', [RouteController::class, 'show'])->name('course');

    Route::view('studente/corso/{id}', 'studente.corso')->name('studente.corso');

    Route::get('lezione/{id_corso}/{id_lezione}', [StudenteController::class, 'lezione'])->name('lezione');
    Route::get('esercizio/{id_corso}/{id_esercizio}', [StudenteController::class, 'esercizio'])->name('esercizio');

    Route::view('carrello', 'public.visualizza-carrello')->name('carrello');

    Route::get('carrello/add/{id}/{type}', [AcquistiController::class, 'aggiungi_al_carrello']);

    Route::delete('carrello/remove/{id}/{type}', [AcquistiController::class, 'rimuovi_dal_carrello']);

    Route::post('prepara-pagamento', [AcquistiController::class, 'prepara_pagamento']);

    Route::get('processa_pagamento', [AcquistiController::class, 'processa_pagamento']);

    Route::get('acquisto-effettuato', [AcquistiController::class, 'processa_acquisto']);
    Route::view('acquisto-a-buon-fine', 'public.acquisto-effettuato');

    Route::view('paga', 'studente.paga');

    Route::view('pagamento-ok', 'studente.pagamento-ok');

    Route::view('payment/extra', 'studente.pagamento-extra')->name('extra-payment');

    Route::view('ordini', 'studente.ordini')->name('ordini');
    Route::view('ordine-{id}', 'studente.ordine');

    Route::view('fatture-studente', 'studente.fatture-studente')->name('fatture-studente');
    Route::view('fattura-{id}', 'studente.fattura');

    Route::view('fattura0-studente-{id}', 'studente.fattura-studente')->name('fattura0-studente');

    Route::view('richieste-dirette', 'studente.richieste-dirette')->name('richieste-dirette');
    Route::view('richieste-dirette-acquistate', 'studente.richieste-dirette-acquistate')
        ->name('richieste-dirette-aquistate');
    Route::get('visualizza-richiesta-studente/{id}', function ($id) {
        return view('studente.visualizza-richiesta-lezione', compact('id'));
    })->name('visualizza-richiesta-studente');

    Route::post('chat/studente/invia-messaggio', [AjaxController::class, 'invia_messaggio']);

    Route::view('recensione', 'studente.recensione')->name('recensione');

    Route::get('invia-feedback-{punteggio}', [AjaxController::class, 'invia_feedback']);
    Route::get('invia-recensione-{testo}', [AjaxController::class, 'invia_recensione']);

    Route::get('cambia_tabella_ordini_studente', [AjaxController::class, 'getOrdini']);
});
