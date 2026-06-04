<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Student\DirectRequestController;
use App\Http\Controllers\Student\InvoiceController;
use App\Http\Controllers\Student\OrderController;
use App\Http\Controllers\Student\RouteController;
use App\Http\Controllers\Student\ReviewController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:student'])->group(function () {
    Route::view('student/dashboard', 'layouts.student-dashboard')->name('student.dashboard');

    Route::view('student/account', 'student.account')->name('student.account');
    Route::view('student/account/profile', 'student.profile')->name('student.account.profile');
    Route::view('student/account/credentials', 'student.credentials')->name('student.account.credentials');
    Route::post('student/account/address', [StudentController::class, 'updateAddress'])
        ->name('student.account.address.update');
    Route::post('student/account/email', [StudentController::class, 'updateEmail'])
        ->name('student.account.email.update');
    Route::post('student/account/password', [StudentController::class, 'updatePassword'])
        ->name('student.account.password.update');

    Route::get('student/courses', [CourseController::class, 'mieiCorsi'])->name('student.courses.index');
    Route::get('student/courses/{id}', [RouteController::class, 'show'])->name('student.courses.show');
    Route::get('student/courses/{course}/lessons/{lesson}', [StudentController::class, 'showLesson'])
        ->name('student.lessons.show');
    Route::get('student/courses/{course}/exercises/{exercise}', [StudentController::class, 'showExercise'])
        ->name('student.exercises.show');

    Route::view('cart', 'public.cart')->name('cart.show');
    Route::get('cart/items/{id}/{type}', [PurchaseController::class, 'addToCart'])
        ->name('cart.items.store');
    Route::delete('cart/items/{id}/{type}', [PurchaseController::class, 'removeFromCart'])
        ->name('cart.items.destroy');

    Route::view('checkout', 'public.checkout')->name('checkout.show');
    Route::post('checkout/payment', [PurchaseController::class, 'preparePayment'])
        ->name('checkout.payment.prepare');
    Route::post('payment/process', [PurchaseController::class, 'processPayment'])
        ->name('payment.process');
    Route::get('payment/process', [PurchaseController::class, 'processIndividualPayment'])
        ->name('payment.process.legacy');
    Route::get('payment/success', [PurchaseController::class, 'completePurchase'])
        ->name('payment.success');
    Route::view('payment/complete', 'public.purchase-complete')->name('payment.complete');
    Route::view('payment/pay', 'student.pay')->name('payment.pay');
    Route::view('payment/ok', 'student.payment-success')->name('payment.ok');
    Route::view('payment/extra', 'student.extra-payment')->name('payment.extra');

    Route::get('student/orders', [OrderController::class, 'index'])->name('student.orders.index');
    Route::get('student/orders/{id}', [OrderController::class, 'show'])->name('student.orders.show');
    Route::get('student/invoices', [InvoiceController::class, 'index'])->name('student.invoices.index');
    Route::get('student/invoices/{id}', [InvoiceController::class, 'showOrderInvoice'])->name('student.invoices.show');
    Route::get('student/invoice-sheets/{id}', [InvoiceController::class, 'showInvoiceSheet'])->name('student.invoice-sheets.show');

    Route::get('student/direct-requests', [DirectRequestController::class, 'index'])->name('student.direct-requests.index');
    Route::get('student/direct-requests/purchased', [DirectRequestController::class, 'purchased'])
        ->name('student.direct-requests.purchased');
    Route::get('student/direct-requests/{id}', [StudentController::class, 'showDirectRequest'])
        ->name('student.direct-requests.show');

    Route::post('student/chat/messages', [AjaxController::class, 'sendMessage'])
        ->name('student.chat.messages.store');
    Route::get('student/review', [ReviewController::class, 'show'])->name('student.review');
    Route::post('student/feedback', [AjaxController::class, 'storeFeedback'])
        ->name('student.feedback.store');
    Route::post('student/review', [AjaxController::class, 'storeReview'])
        ->name('student.review.store');
    Route::get('student/orders-table', [OrderController::class, 'table'])
        ->name('student.orders.table');
});
