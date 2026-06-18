<?php

use App\Http\Controllers\AccountCredentialsController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\LessonRequestController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

foreach ([
    'themeArea',
    'subject',
    'course',
    'lesson',
    'exercise',
    'lessonRequest',
    'order',
    'invoice',
    'chat',
] as $parameter) {
    Route::pattern($parameter, '[0-9]+');
}

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
        Route::put('theme-areas/{themeArea}', [ThemeAreaController::class, 'update'])
            ->name('theme-areas.update');
        Route::delete('theme-areas/{themeArea}', [ThemeAreaController::class, 'destroy'])
            ->name('theme-areas.destroy');

        Route::get('subjects', [SubjectController::class, 'index'])->name('subjects.index');
        Route::post('subjects', [SubjectController::class, 'store'])->name('subjects.store');
        Route::put('subjects/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
        Route::delete('subjects/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');

        Route::get('courses', [CourseController::class, 'list'])->name('courses.index');
        Route::get('courses/create', [CourseController::class, 'index'])->name('courses.create');
        Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('courses/{course}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
        Route::get('courses/{course}/lessons/{lesson}/edit', [LessonController::class, 'edit'])
            ->scopeBindings()
            ->name('lessons.edit');
        Route::post('lessons', [LessonController::class, 'store'])->name('lessons.store');
        Route::put('lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
        Route::delete('lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
        Route::post('lessons/upload-presentation', [LessonController::class, 'uploadPresentation'])
            ->name('lessons.upload-presentation.store');
        Route::delete('lessons/upload-presentation', [LessonController::class, 'deletePresentationSession'])
            ->name('lessons.upload-presentation.destroy');
        Route::post('lessons/upload-file', [LessonController::class, 'uploadLessonFile'])
            ->name('lessons.upload-file.store');
        Route::delete('lessons/upload-file', [LessonController::class, 'deleteLessonSession'])
            ->name('lessons.upload-file.destroy');
        Route::post('lessons/{lesson}/presentation', [LessonController::class, 'updatePresentation'])
            ->name('lessons.presentation.update');
        Route::post('lessons/{lesson}/file', [LessonController::class, 'updateLessonFile'])
            ->name('lessons.file.update');

        Route::get('courses/{course}/exercises/create', [ExerciseController::class, 'create'])->name('exercises.create');
        Route::get('courses/{course}/exercises/{exercise}/edit', [ExerciseController::class, 'edit'])
            ->scopeBindings()
            ->name('exercises.edit');
        Route::post('exercises', [ExerciseController::class, 'store'])->name('exercises.store');
        Route::put('exercises/{exercise}', [ExerciseController::class, 'update'])->name('exercises.update');
        Route::delete('exercises/{exercise}', [ExerciseController::class, 'destroy'])->name('exercises.destroy');
        Route::post('exercises/trace/upload', [ExerciseController::class, 'uploadTrace'])
            ->name('exercises.trace.upload.store');
        Route::delete('exercises/trace/session', [ExerciseController::class, 'clearTraceSession'])
            ->name('exercises.trace.session.destroy');
        Route::post('exercises/execution/upload', [ExerciseController::class, 'uploadExecution'])
            ->name('exercises.execution.upload.store');
        Route::delete('exercises/execution/session', [ExerciseController::class, 'clearExecutionSession'])
            ->name('exercises.execution.session.destroy');
        Route::post('exercises/{exercise}/trace', [ExerciseController::class, 'updateTrace'])
            ->name('exercises.trace.update');
        Route::post('exercises/{exercise}/execution', [ExerciseController::class, 'updateExecution'])
            ->name('exercises.execution.update');

        Route::view('students', 'admin.students.students')->name('students.index');
        Route::get('lesson-requests', [LessonRequestController::class, 'index'])
            ->name('lesson-requests.index');
        Route::get('lesson-requests/{lessonRequest}', [LessonRequestController::class, 'show'])
            ->name('lesson-requests.show');
        Route::post('lesson-requests/{lessonRequest}/solution', [LessonRequestController::class, 'storeSolution'])
            ->name('lesson-requests.solution.store');
        Route::delete('lesson-requests/{lessonRequest}/solution', [LessonRequestController::class, 'destroySolution'])
            ->name('lesson-requests.solution.destroy');
        Route::post('lesson-requests/{lessonRequest}/price', [LessonRequestController::class, 'storePrice'])
            ->name('lesson-requests.price.store');

        Route::get('sales', [BillingController::class, 'sales'])->name('sales.index');
        Route::get('orders-table', [BillingController::class, 'ordersTable'])->name('orders.table');
        Route::get('orders/{order}', [BillingController::class, 'showOrder'])->name('orders.show');
        Route::get('orders/{order}/invoice', [BillingController::class, 'showInvoice'])->name('orders.invoice');

        Route::get('invoices', [InvoiceController::class, 'showAll'])->name('invoices.index');
        Route::view('invoices/extra', 'admin.billing.extra-invoice')->name('invoices.extra');
        Route::post('invoices/extra', [PurchaseController::class, 'createExtraInvoice'])->name('invoices.extra.store');
        Route::view('invoices/created', 'admin.billing.invoice-created')->name('invoices.created');
        Route::get('invoices/{invoice:number}', [InvoiceController::class, 'show'])->name('invoices.show');

        Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
        Route::get('chats/{chat}', [ChatController::class, 'show'])->name('chats.show');
        Route::post('chat/messages', [AjaxController::class, 'sendMessage'])->name('chat.messages.store');
    });
