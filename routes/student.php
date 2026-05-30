<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Student\RouteController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::view('student/dashboard', 'layouts.dashboard-studente')->name('student.dashboard');

    Route::view('student/account', 'studente.impostazioni-account')->name('student.account');
    Route::view('student/account/profile', 'studente.mod-dati-pers')->name('student.account.profile');
    Route::view('student/account/credentials', 'studente.mod-cred')->name('student.account.credentials');
    Route::post('student/account/address', [StudentController::class, 'updateAddress'])
        ->name('student.account.address.update');
    Route::post('student/account/email', [StudentController::class, 'updateEmail'])
        ->name('student.account.email.update');
    Route::post('student/account/password', [StudentController::class, 'updatePassword'])
        ->name('student.account.password.update');

    Route::get('student/courses', [CourseController::class, 'mieiCorsi'])->name('student.courses.index');
    Route::get('student/courses/{id}', [RouteController::class, 'show'])->name('student.courses.show');
    Route::get('student/courses/{id_corso}/lessons/{id_lezione}', [StudentController::class, 'showLesson'])
        ->name('student.lessons.show');
    Route::get('student/courses/{id_corso}/exercises/{id_esercizio}', [StudentController::class, 'showExercise'])
        ->name('student.exercises.show');

    Route::view('cart', 'public.visualizza-carrello')->name('cart.show');
    Route::get('cart/items/{id}/{type}', [PurchaseController::class, 'addToCart'])
        ->name('cart.items.store');
    Route::delete('cart/items/{id}/{type}', [PurchaseController::class, 'removeFromCart'])
        ->name('cart.items.destroy');

    Route::view('checkout', 'public.acquista')->name('checkout.show');
    Route::post('checkout/payment', [PurchaseController::class, 'preparePayment'])
        ->name('checkout.payment.prepare');
    Route::post('payment/process', [PurchaseController::class, 'processPayment'])
        ->name('payment.process');
    Route::get('payment/process', [PurchaseController::class, 'processIndividualPayment'])
        ->name('payment.process.legacy');
    Route::get('payment/success', [PurchaseController::class, 'completePurchase'])
        ->name('payment.success');
    Route::view('payment/complete', 'public.acquisto-effettuato')->name('payment.complete');
    Route::view('payment/pay', 'studente.paga')->name('payment.pay');
    Route::view('payment/ok', 'studente.pagamento-ok')->name('payment.ok');
    Route::view('payment/extra', 'studente.pagamento-extra')->name('payment.extra');

    Route::view('student/orders', 'studente.ordini')->name('student.orders.index');
    Route::view('student/orders/{id}', 'studente.ordine')->name('student.orders.show');
    Route::view('student/invoices', 'studente.fatture-studente')->name('student.invoices.index');
    Route::view('student/invoices/{id}', 'studente.fattura')->name('student.invoices.show');
    Route::view('student/invoice-sheets/{id}', 'studente.fattura-studente')->name('student.invoice-sheets.show');

    Route::view('student/direct-requests', 'studente.richieste-dirette')->name('student.direct-requests.index');
    Route::view('student/direct-requests/purchased', 'studente.richieste-dirette-acquistate')
        ->name('student.direct-requests.purchased');
    Route::get('student/direct-requests/{id}', [StudentController::class, 'showDirectRequest'])
        ->name('student.direct-requests.show');

    Route::post('student/chat/messages', [AjaxController::class, 'sendMessage'])
        ->name('student.chat.messages.store');
    Route::get('student/chats/{id_chat}/messages', [AjaxController::class, 'getStudentMessages'])
        ->name('student.chats.messages.index');
    Route::view('student/review', 'studente.recensione')->name('student.review');
    Route::post('student/feedback', [AjaxController::class, 'storeFeedback'])
        ->name('student.feedback.store');
    Route::post('student/review', [AjaxController::class, 'storeReview'])
        ->name('student.review.store');
    Route::get('student/orders-table', [AjaxController::class, 'getOrders'])
        ->name('student.orders.table');
});
