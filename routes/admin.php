<?php

use App\Http\Controllers\AccountCredentialsController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Public\LessonRequestController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::view('dashboard', 'layouts.admin-dashboard')->name('dashboard');

        Route::view('account', 'admin.settings.account')->name('account');
        Route::view('account/profile', 'admin.settings.profile')->name('account.profile');
        Route::get('account/credentials', [AccountCredentialsController::class, 'showAdmin'])
            ->name('account.credentials');
        Route::get('account/photo', [AccountController::class, 'photo'])->name('account.photo');
        Route::get('account/address', [AccountController::class, 'address'])->name('account.address');
        Route::get('account/certificates', [AccountController::class, 'certificatesIndex'])
            ->name('account.certificates.index');
        Route::get('account/certificates/create', [AccountController::class, 'createCertificate'])
            ->name('account.certificates.create');
        Route::get('account/vat-number', [AccountController::class, 'vatNumber'])->name('account.vat-number');

        Route::post('account/address', [AccountController::class, 'updateAddress'])
            ->name('account.address.update');
        Route::post('account/photo', [AccountController::class, 'updatePhoto'])
            ->name('account.photo.update');
        Route::post('account/certificates/file', [AccountController::class, 'updateCertificateFile'])
            ->name('account.certificates.file.update');
        Route::post('account/certificates/uploads', [AccountController::class, 'storeCertificateUpload'])
            ->name('account.certificates.uploads.store');
        Route::delete('account/certificates/uploads', [AccountController::class, 'destroyCertificateUpload'])
            ->name('account.certificates.uploads.destroy');
        Route::post('account/certificates/name', [AccountController::class, 'updateCertificateName'])
            ->name('account.certificates.name.update');
        Route::delete('account/certificates', [AccountController::class, 'destroyCertificate'])
            ->name('account.certificates.destroy');
        Route::post('account/certificates', [AccountController::class, 'storeCertificate'])
            ->name('account.certificates.store');
        Route::post('account/email', [AccountCredentialsController::class, 'updateEmail'])
            ->name('account.email.update');
        Route::post('account/password', [AccountCredentialsController::class, 'updatePassword'])
            ->name('account.password.update');
        Route::post('account/vat-number', [AccountController::class, 'updateVatNumber'])
            ->name('account.vat-number.update');

        Route::view('teaching', 'admin.teaching.teaching')->name('teaching.index');

        Route::get('theme-areas', [ThemeAreaController::class, 'index'])->name('theme-areas.index');
        Route::post('theme-areas', [ThemeAreaController::class, 'store'])->name('theme-areas.store');
        Route::put('theme-areas/{id}', [ThemeAreaController::class, 'update'])->whereNumber('id')->name('theme-areas.update');
        Route::delete('theme-areas/{id}', [ThemeAreaController::class, 'destroy'])->whereNumber('id')->name('theme-areas.destroy');

        Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
        Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::put('subjects/{id}', [SubjectController::class, 'update'])->whereNumber('id')->name('subjects.update');
        Route::delete('subjects/{id}', [SubjectController::class, 'destroy'])->whereNumber('id')->name('subjects.destroy');

        Route::get('courses', [CourseController::class, 'list'])->name('courses.index');
        Route::get('courses/create', [CourseController::class, 'index'])->name('courses.create');
        Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('courses/{id}/edit', [CourseController::class, 'edit'])->whereNumber('id')->name('courses.edit');
        Route::put('courses/{id}', [CourseController::class, 'update'])->whereNumber('id')->name('courses.update');
        Route::delete('courses/{id}', [CourseController::class, 'destroy'])->whereNumber('id')->name('courses.destroy');

        Route::get('courses/{id}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
        Route::get('courses/{course}/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
        Route::post('lessons', [LessonController::class, 'store'])->name('lessons.store');
        Route::put('lessons/{id}', [LessonController::class, 'update'])->whereNumber('id')->name('lessons.update');
        Route::delete('lessons/{id}', [LessonController::class, 'destroy'])->whereNumber('id')->name('lessons.destroy');
        Route::post('lessons/upload-presentation', [LessonController::class, 'uploadPresentation'])
            ->name('lessons.upload-presentation.store');
        Route::delete('lessons/upload-presentation', [LessonController::class, 'deletePresentationSession'])
            ->name('lessons.upload-presentation.destroy');
        Route::post('lessons/upload-file', [LessonController::class, 'uploadLessonFile'])
            ->name('lessons.upload-file.store');
        Route::delete('lessons/upload-file', [LessonController::class, 'deleteLessonSession'])
            ->name('lessons.upload-file.destroy');
        Route::post('lessons/{id}/presentation', [LessonController::class, 'updatePresentation'])->whereNumber('id')
            ->name('lessons.presentation.update');
        Route::post('lessons/{id}/file', [LessonController::class, 'updateLessonFile'])->whereNumber('id')
            ->name('lessons.file.update');

        Route::get('courses/{course}/exercises/create', [ExerciseController::class, 'create'])->name('exercises.create');
        Route::get('courses/{course}/exercises/{exercise}/edit', [ExerciseController::class, 'edit'])->name('exercises.edit');
        Route::post('exercises', [ExerciseController::class, 'store'])->name('exercises.store');
        Route::put('exercises/{id}', [ExerciseController::class, 'update'])->whereNumber('id')->name('exercises.update');
        Route::delete('exercises/{id}', [ExerciseController::class, 'destroy'])->whereNumber('id')->name('exercises.destroy');
        Route::post('exercises/trace/upload', [ExerciseController::class, 'uploadTrace'])
            ->name('exercises.trace.upload.store');
        Route::delete('exercises/trace/session', [ExerciseController::class, 'clearTraceSession'])
            ->name('exercises.trace.session.destroy');
        Route::post('exercises/execution/upload', [ExerciseController::class, 'uploadExecution'])
            ->name('exercises.execution.upload.store');
        Route::delete('exercises/execution/session', [ExerciseController::class, 'clearExecutionSession'])
            ->name('exercises.execution.session.destroy');
        Route::post('exercises/{id}/trace', [ExerciseController::class, 'updateTrace'])->whereNumber('id')
            ->name('exercises.trace.update');
        Route::post('exercises/{id}/execution', [ExerciseController::class, 'updateExecution'])->whereNumber('id')
            ->name('exercises.execution.update');

        Route::view('students', 'admin.students.students')->name('students.index');
        Route::get('lesson-requests', [LessonRequestController::class, 'index'])
            ->name('lesson-requests.index');
        Route::get('lesson-requests/{id}', [LessonRequestController::class, 'show'])->whereNumber('id')
            ->name('lesson-requests.show');
        Route::post('lesson-requests/{id}/solution', [LessonRequestController::class, 'storeSolution'])->whereNumber('id')
            ->name('lesson-requests.solution.store');
        Route::delete('lesson-requests/{id}/solution', [LessonRequestController::class, 'destroySolution'])->whereNumber('id')
            ->name('lesson-requests.solution.destroy');
        Route::post('lesson-requests/{id}/price', [LessonRequestController::class, 'storePrice'])->whereNumber('id')
            ->name('lesson-requests.price.store');

        Route::get('sales', [BillingController::class, 'sales'])->name('sales.index');
        Route::get('orders-table', [BillingController::class, 'ordersTable'])->name('orders.table');
        Route::get('orders/{id}', [BillingController::class, 'showOrder'])->whereNumber('id')->name('orders.show');
        Route::get('orders/{id}/invoice', [BillingController::class, 'showInvoice'])->whereNumber('id')->name('orders.invoice');

        Route::get('invoices', [InvoiceController::class, 'showAll'])->name('invoices.index');
        Route::view('invoices/extra', 'admin.billing.extra-invoice')->name('invoices.extra');
        Route::post('invoices/extra', [PurchaseController::class, 'createExtraInvoice'])->name('invoices.extra.store');
        Route::view('invoices/created', 'admin.billing.invoice-created')->name('invoices.created');
        Route::get('invoices/{id}', [InvoiceController::class, 'show'])->whereNumber('id')->name('invoices.show');

        Route::get('chats', [LessonRequestController::class, 'studentChats'])->name('chats.index');
        Route::get('chats/{id}', [LessonRequestController::class, 'showChat'])->whereNumber('id')->name('chats.show');
        Route::post('chat/messages', [AjaxController::class, 'sendMessage'])->name('chat.messages.store');
    });
