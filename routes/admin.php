<?php

use App\Http\Controllers\AcquistiController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExerciseController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\MatterController;
use App\Http\Controllers\Admin\ModDatiAdminController;
use App\Http\Controllers\Admin\ThemeAreaController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Public\LessonOnRequestController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::view('dashboard', 'layouts.dashboard-admin')->name('dashboard');

        Route::view('account', 'admin.settings.imp-account')->name('account');
        Route::view('account/profile', 'admin.settings.mod-dati-pers')->name('account.profile');
        Route::view('account/credentials', 'admin.settings.mod-cred')->name('account.credentials');
        Route::view('account/photo', 'admin.settings.mod-foto')->name('account.photo');
        Route::view('account/address', 'admin.settings.mod-indirizzo')->name('account.address');
        Route::view('account/certificates', 'admin.settings.mod-certif')->name('account.certificates.index');
        Route::view('account/certificates/create', 'admin.settings.add-certif')->name('account.certificates.create');
        Route::view('account/vat-number', 'admin.settings.mod-part-iva')->name('account.vat-number');

        Route::post('account/address', [ModDatiAdminController::class, 'mod_ind'])
            ->name('account.address.update');
        Route::post('account/photo', [ModDatiAdminController::class, 'upload_foto'])
            ->name('account.photo.update');
        Route::post('account/certificates/photo', [ModDatiAdminController::class, 'upload_cert'])
            ->name('account.certificates.photo.update');
        Route::post('account/certificates/uploads', [ModDatiAdminController::class, 'upload_cert_session'])
            ->name('account.certificates.uploads.store');
        Route::delete('account/certificates/uploads', [ModDatiAdminController::class, 'elimina_cert_session'])
            ->name('account.certificates.uploads.destroy');
        Route::post('account/certificates/name', [ModDatiAdminController::class, 'modifica_nome_cert'])
            ->name('account.certificates.name.update');
        Route::delete('account/certificates', [ModDatiAdminController::class, 'elimina_cert'])
            ->name('account.certificates.destroy');
        Route::post('account/certificates', [ModDatiAdminController::class, 'add_cert_admin'])
            ->name('account.certificates.store');
        Route::post('account/email', [ModDatiAdminController::class, 'mod_email_admin'])
            ->name('account.email.update');
        Route::post('account/password', [ModDatiAdminController::class, 'mod_pass_admin'])
            ->name('account.password.update');
        Route::post('account/vat-number', [ModDatiAdminController::class, 'mod_piva'])
            ->name('account.vat-number.update');

        Route::view('teaching', 'admin.teaching.insegnamento')->name('teaching.index');

        Route::get('theme-areas', [ThemeAreaController::class, 'index'])->name('theme-areas.index');
        Route::post('theme-areas', [ThemeAreaController::class, 'store'])->name('theme-areas.store');
        Route::put('theme-areas/{id}', [ThemeAreaController::class, 'update'])->name('theme-areas.update');
        Route::delete('theme-areas/{id}', [ThemeAreaController::class, 'destroy'])->name('theme-areas.destroy');

        Route::get('subjects', [MatterController::class, 'index'])->name('subjects.index');
        Route::post('subjects', [MatterController::class, 'store'])->name('subjects.store');
        Route::put('subjects/{id}', [MatterController::class, 'update'])->name('subjects.update');
        Route::delete('subjects/{id}', [MatterController::class, 'destroy'])->name('subjects.destroy');

        Route::get('courses', [CourseController::class, 'list'])->name('courses.index');
        Route::get('courses/create', [CourseController::class, 'index'])->name('courses.create');
        Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{id}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

        Route::get('courses/{id}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
        Route::view('courses/{id_corso}/lessons/{id_lezione}/edit', 'admin.teaching.modifica-lezione')
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

        Route::view('courses/{course}/exercises/create', 'admin.teaching.nuovo-esercizio')->name('exercises.create');
        Route::view('courses/{course}/exercises/{exercise}/edit', 'admin.teaching.modifica-esercizio')
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

        Route::view('students', 'admin.students.studenti')->name('students.index');
        Route::get('lesson-requests', [LessonOnRequestController::class, 'index'])
            ->name('lesson-requests.index');
        Route::get('lesson-requests/{id}', [LessonOnRequestController::class, 'visualizzaRichiesta'])
            ->name('lesson-requests.show');
        Route::post('lesson-requests/{id}/solution', [LessonOnRequestController::class, 'sol_rich_upload'])
            ->name('lesson-requests.solution.store');
        Route::delete('lesson-requests/{id}/solution', [LessonOnRequestController::class, 'lez_rich_rem_exec'])
            ->name('lesson-requests.solution.destroy');
        Route::post('lesson-requests/{id}/price', [LessonOnRequestController::class, 'carica_prezzo_lez_rich'])
            ->name('lesson-requests.price.store');

        Route::get('sales', [BillingController::class, 'vendite'])->name('sales.index');
        Route::get('orders-table', [BillingController::class, 'cambiaTabellaOrdini'])->name('orders.table');
        Route::get('orders/{id}', [BillingController::class, 'showOrder'])->name('orders.show');
        Route::get('orders/{id}/invoice', [BillingController::class, 'showInvoice'])->name('orders.invoice');

        Route::get('invoices', [InvoiceController::class, 'showAll'])->name('invoices.index');
        Route::view('invoices/extra', 'admin.billing.fattura-extra')->name('invoices.extra');
        Route::post('invoices/extra', [AcquistiController::class, 'crea_fattura'])->name('invoices.extra.store');
        Route::view('invoices/created', 'admin.billing.fattura-creata')->name('invoices.created');
        Route::get('invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');

        Route::get('chats', [LessonOnRequestController::class, 'chatStudenti'])->name('chats.index');
        Route::get('chats/{id}', [LessonOnRequestController::class, 'visualizzaChat'])->name('chats.show');
        Route::post('chat/messages', [AjaxController::class, 'invia_messaggio'])->name('chat.messages.store');
        Route::get('chats/{id_chat}/messages', [AjaxController::class, 'leggi_messaggi'])
            ->name('chats.messages.index');
    });
