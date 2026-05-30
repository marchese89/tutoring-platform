<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Public\LessonRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::view('dashboard', 'layouts.admin-dashboard')->name('dashboard');

        Route::view('account', 'admin.settings.account')->name('account');
        Route::view('account/profile', 'admin.settings.profile')->name('account.profile');
        Route::view('account/credentials', 'admin.settings.credentials')->name('account.credentials');
        Route::view('account/photo', 'admin.settings.photo')->name('account.photo');
        Route::view('account/address', 'admin.settings.address')->name('account.address');
        Route::view('account/certificates', 'admin.settings.certificates')->name('account.certificates.index');
        Route::view('account/certificates/create', 'admin.settings.create-certificate')->name('account.certificates.create');
        Route::view('account/vat-number', 'admin.settings.vat-number')->name('account.vat-number');

        Route::post('account/address', [AccountController::class, 'updateAddress'])
            ->name('account.address.update');
        Route::post('account/photo', [AccountController::class, 'updatePhoto'])
            ->name('account.photo.update');
        Route::post('account/certificates/photo', [AccountController::class, 'updateCertificateFile'])
            ->name('account.certificates.photo.update');
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
        Route::post('account/email', [AccountController::class, 'updateEmail'])
            ->name('account.email.update');
        Route::post('account/password', [AccountController::class, 'updatePassword'])
            ->name('account.password.update');
        Route::post('account/vat-number', [AccountController::class, 'updateVatNumber'])
            ->name('account.vat-number.update');

        Route::view('teaching', 'admin.teaching.teaching')->name('teaching.index');

        Route::get('theme-areas', [ThemeAreaController::class, 'index'])->name('theme-areas.index');
        Route::post('theme-areas', [ThemeAreaController::class, 'store'])->name('theme-areas.store');
        Route::put('theme-areas/{id}', [ThemeAreaController::class, 'update'])->name('theme-areas.update');
        Route::delete('theme-areas/{id}', [ThemeAreaController::class, 'destroy'])->name('theme-areas.destroy');

        Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
        Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::put('subjects/{id}', [SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('subjects/{id}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

        Route::get('courses', [CourseController::class, 'list'])->name('courses.index');
        Route::get('courses/create', [CourseController::class, 'index'])->name('courses.create');
        Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{id}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('courses/{id}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
        Route::view('courses/{id_corso}/lessons/{id_lezione}/edit', 'admin.teaching.edit-lesson')
            ->name('lessons.edit');
        Route::post('lessons', [LessonController::class, 'store'])->name('lessons.store');
        Route::put('lessons/{id}', [LessonController::class, 'update'])->name('lessons.update');
        Route::delete('lessons/{id}', [LessonController::class, 'destroy'])->name('lessons.destroy');
        Route::post('lessons/upload-presentation', [LessonController::class, 'uploadPresentation'])
            ->name('lessons.upload-presentation.store');
        Route::delete('lessons/upload-presentation', [LessonController::class, 'deletePresentationSession'])
            ->name('lessons.upload-presentation.destroy');
        Route::post('lessons/upload-file', [LessonController::class, 'uploadLessonFile'])
            ->name('lessons.upload-file.store');
        Route::delete('lessons/upload-file', [LessonController::class, 'deleteLessonSession'])
            ->name('lessons.upload-file.destroy');
        Route::post('lessons/{id}/presentation', [LessonController::class, 'updatePresentation'])
            ->name('lessons.presentation.update');
        Route::post('lessons/{id}/file', [LessonController::class, 'updateLessonFile'])
            ->name('lessons.file.update');

        Route::view('courses/{course}/exercises/create', 'admin.teaching.create-exercise')->name('exercises.create');
        Route::view('courses/{course}/exercises/{exercise}/edit', 'admin.teaching.edit-exercise')
            ->name('exercises.edit');
        Route::post('exercises', [ExerciseController::class, 'store'])->name('exercises.store');
        Route::put('exercises/{id}', [ExerciseController::class, 'update'])->name('exercises.update');
        Route::delete('exercises/{id}', [ExerciseController::class, 'destroy'])->name('exercises.destroy');
        Route::post('exercises/trace/upload', [ExerciseController::class, 'uploadTrace'])
            ->name('exercises.trace.upload.store');
        Route::delete('exercises/trace/session', [ExerciseController::class, 'clearTraceSession'])
            ->name('exercises.trace.session.destroy');
        Route::post('exercises/execution/upload', [ExerciseController::class, 'uploadExecution'])
            ->name('exercises.execution.upload.store');
        Route::delete('exercises/execution/session', [ExerciseController::class, 'clearExecutionSession'])
            ->name('exercises.execution.session.destroy');
        Route::post('exercises/{id}/trace', [ExerciseController::class, 'updateTrace'])
            ->name('exercises.trace.update');
        Route::post('exercises/{id}/execution', [ExerciseController::class, 'updateExecution'])
            ->name('exercises.execution.update');

        Route::view('students', 'admin.students.students')->name('students.index');
        Route::get('lesson-requests', [LessonRequestController::class, 'index'])
            ->name('lesson-requests.index');
        Route::get('lesson-requests/{id}', [LessonRequestController::class, 'show'])
            ->name('lesson-requests.show');
        Route::post('lesson-requests/{id}/solution', [LessonRequestController::class, 'storeSolution'])
            ->name('lesson-requests.solution.store');
        Route::delete('lesson-requests/{id}/solution', [LessonRequestController::class, 'destroySolution'])
            ->name('lesson-requests.solution.destroy');
        Route::post('lesson-requests/{id}/price', [LessonRequestController::class, 'storePrice'])
            ->name('lesson-requests.price.store');

        Route::get('sales', [BillingController::class, 'sales'])->name('sales.index');
        Route::get('orders-table', [BillingController::class, 'ordersTable'])->name('orders.table');
        Route::get('orders/{id}', [BillingController::class, 'showOrder'])->name('orders.show');
        Route::get('orders/{id}/invoice', [BillingController::class, 'showInvoice'])->name('orders.invoice');

        Route::get('invoices', [InvoiceController::class, 'showAll'])->name('invoices.index');
        Route::view('invoices/extra', 'admin.billing.extra-invoice')->name('invoices.extra');
        Route::post('invoices/extra', [PurchaseController::class, 'createExtraInvoice'])->name('invoices.extra.store');
        Route::view('invoices/created', 'admin.billing.invoice-created')->name('invoices.created');
        Route::get('invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

        Route::get('chats', [LessonRequestController::class, 'studentChats'])->name('chats.index');
        Route::get('chats/{id}', [LessonRequestController::class, 'showChat'])->name('chats.show');
        Route::post('chat/messages', [AjaxController::class, 'sendMessage'])->name('chat.messages.store');
        Route::get('chats/{id_chat}/messages', [AjaxController::class, 'getMessages'])
            ->name('chats.messages.index');
    });
