<?php

use App\Models\Course;
use App\Models\LessonRequest;
use App\Models\Subject;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Public

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('theme-areas.index', function (BreadcrumbTrail $trail) {
    $trail->push('Aree tematiche', route('theme-areas.index'));
});

Breadcrumbs::for('subjects.index', function (BreadcrumbTrail $trail, $id_at) {
    $trail->parent('theme-areas.index');
    $trail->push('Materie', route('subjects.index', $id_at));
});

Breadcrumbs::for('courses.index', function (BreadcrumbTrail $trail, $id_materia) {
    $subject = Subject::find($id_materia);

    if ($subject) {
        $trail->parent('subjects.index', $subject->theme_area_id);
    } else {
        $trail->parent('theme-areas.index');
    }

    $trail->push('Corsi', route('courses.index', $id_materia));
});

Breadcrumbs::for('courses.show', function (BreadcrumbTrail $trail, $id) {
    $course = Course::find($id);

    if ($course) {
        $trail->parent('courses.index', $course->subject_id);
    } else {
        $trail->parent('theme-areas.index');
    }

    $trail->push('Corso', route('courses.show', $id));
});

Breadcrumbs::for('lessons.presentation', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('courses.show', $id_corso);
    $trail->push('Presentazione lezione', route('lessons.presentation', [
        'id_corso' => $id_corso,
        'id_lezione' => $id_lezione,
    ]));
});

Breadcrumbs::for('lessons.show', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('courses.show', $id_corso);
    $trail->push('Visualizza lezione', route('lessons.show', [
        'id_corso' => $id_corso,
        'id_lezione' => $id_lezione,
    ]));
});

Breadcrumbs::for('exercises.trace', function (BreadcrumbTrail $trail, $id_corso, $id_esercizio) {
    $trail->parent('courses.show', $id_corso);
    $trail->push('Traccia esercizio', route('exercises.trace', [
        'id_corso' => $id_corso,
        'id_esercizio' => $id_esercizio,
    ]));
});

// Admin - dashboard

Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

// Admin - account

Breadcrumbs::for('admin.account', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Impostazioni account', route('admin.account'));
});

Breadcrumbs::for('admin.account.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account');
    $trail->push('Modifica dati personali', route('admin.account.profile'));
});

Breadcrumbs::for('admin.account.credentials', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account');
    $trail->push('Modifica credenziali', route('admin.account.credentials'));
});

Breadcrumbs::for('admin.account.photo', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push('Modifica foto', route('admin.account.photo'));
});

Breadcrumbs::for('admin.account.address', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push('Modifica indirizzo', route('admin.account.address'));
});

Breadcrumbs::for('admin.account.certificates.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push('Modifica certificati', route('admin.account.certificates.index'));
});

Breadcrumbs::for('admin.account.certificates.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.certificates.index');
    $trail->push('Aggiungi certificato', route('admin.account.certificates.create'));
});

Breadcrumbs::for('admin.account.vat-number', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push('Modifica Partita IVA', route('admin.account.vat-number'));
});

// Admin - teaching

Breadcrumbs::for('admin.teaching.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Insegnamento', route('admin.teaching.index'));
});

Breadcrumbs::for('admin.theme-areas.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push('Aree tematiche', route('admin.theme-areas.index'));
});

Breadcrumbs::for('admin.subjects.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push('Materie', route('admin.subjects.index'));
});

Breadcrumbs::for('admin.courses.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push('Nuovo corso', route('admin.courses.create'));
});

Breadcrumbs::for('admin.courses.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push('Elenco corsi', route('admin.courses.index'));
});

Breadcrumbs::for('admin.courses.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.courses.index');
    $trail->push('Modifica dettagli corso', route('admin.courses.edit', $id));
});

Breadcrumbs::for('admin.lessons.create', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.courses.edit', $id);
    $trail->push('Nuova lezione', route('admin.lessons.create', $id));
});

Breadcrumbs::for('admin.lessons.edit', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('admin.courses.edit', $id_corso);
    $trail->push('Modifica lezione', route('admin.lessons.edit', [
        'id_corso' => $id_corso,
        'id_lezione' => $id_lezione,
    ]));
});

Breadcrumbs::for('admin.exercises.create', function (BreadcrumbTrail $trail, $course) {
    $trail->parent('admin.courses.edit', $course);
    $trail->push('Nuovo esercizio', route('admin.exercises.create', $course));
});

Breadcrumbs::for('admin.exercises.edit', function (BreadcrumbTrail $trail, $course, $exercise) {
    $trail->parent('admin.courses.edit', $course);
    $trail->push('Modifica esercizio', route('admin.exercises.edit', [
        'course' => $course,
        'exercise' => $exercise,
    ]));
});

// Admin - students

Breadcrumbs::for('admin.students.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Studenti', route('admin.students.index'));
});

Breadcrumbs::for('admin.lesson-requests.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.students.index');
    $trail->push('Richieste studenti', route('admin.lesson-requests.index'));
});

Breadcrumbs::for('admin.lesson-requests.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.lesson-requests.index');
    $trail->push('Visualizza richiesta', route('admin.lesson-requests.show', $id));
});

Breadcrumbs::for('admin.chats.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.students.index');
    $trail->push('Chat studenti', route('admin.chats.index'));
});

Breadcrumbs::for('admin.chats.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.chats.index');
    $trail->push('Visualizza chat', route('admin.chats.show', $id));
});

// Admin - billing

Breadcrumbs::for('admin.sales.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Vendite', route('admin.sales.index'));
});

Breadcrumbs::for('admin.orders.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.sales.index');
    $trail->push('Ordine', route('admin.orders.show', $id));
});

Breadcrumbs::for('admin.orders.invoice', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.orders.show', $id);
    $trail->push('Fattura', route('admin.orders.invoice', $id));
});

Breadcrumbs::for('admin.invoices.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Fatture', route('admin.invoices.index'));
});

Breadcrumbs::for('admin.invoices.extra', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Fattura extra', route('admin.invoices.extra'));
});

Breadcrumbs::for('admin.invoices.created', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.invoices.extra');
    $trail->push('Fattura creata', route('admin.invoices.created'));
});

Breadcrumbs::for('admin.invoices.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.invoices.index');
    $trail->push('Visualizza fattura', route('admin.invoices.show', $id));
});

// Student - dashboard

Breadcrumbs::for('student.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('student.dashboard'));
});

// Student - account

Breadcrumbs::for('student.account', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Impostazioni account', route('student.account'));
});

Breadcrumbs::for('student.account.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('student.account');
    $trail->push('Modifica dati personali', route('student.account.profile'));
});

Breadcrumbs::for('student.account.credentials', function (BreadcrumbTrail $trail) {
    $trail->parent('student.account');
    $trail->push('Modifica credenziali', route('student.account.credentials'));
});

// Student - courses

Breadcrumbs::for('student.courses.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Corsi', route('student.courses.index'));
});

Breadcrumbs::for('student.courses.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.courses.index');
    $trail->push('Corso', route('student.courses.show', $id));
});

Breadcrumbs::for('student.lessons.show', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('student.courses.show', $id_corso);
    $trail->push('Lezione', route('student.lessons.show', [
        'id_corso' => $id_corso,
        'id_lezione' => $id_lezione,
    ]));
});

Breadcrumbs::for('student.exercises.show', function (BreadcrumbTrail $trail, $id_corso, $id_esercizio) {
    $trail->parent('student.courses.show', $id_corso);
    $trail->push('Esercizio', route('student.exercises.show', [
        'id_corso' => $id_corso,
        'id_esercizio' => $id_esercizio,
    ]));
});

// Student - orders and payments

Breadcrumbs::for('student.orders.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Ordini', route('student.orders.index'));
});

Breadcrumbs::for('student.orders.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.orders.index');
    $trail->push('Ordine', route('student.orders.show', $id));
});

Breadcrumbs::for('student.invoices.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Fatture', route('student.invoices.index'));
});

Breadcrumbs::for('student.invoices.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.orders.show', $id);
    $trail->push('Fattura', route('student.invoices.show', $id));
});

Breadcrumbs::for('student.invoice-sheets.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.invoices.index');
    $trail->push('Fattura', route('student.invoice-sheets.show', $id));
});

Breadcrumbs::for('payment.extra', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Pagamento Extra', route('payment.extra'));
});

Breadcrumbs::for('payment.pay', function (BreadcrumbTrail $trail) {
    $trail->parent('payment.extra');
    $trail->push('Acquista', route('payment.pay'));
});

Breadcrumbs::for('payment.ok', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Fattura creata', route('payment.ok'));
});

// Student - requests and review

Breadcrumbs::for('student.direct-requests.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Richieste Dirette', route('student.direct-requests.index'));
});

Breadcrumbs::for('student.direct-requests.purchased', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Lezioni su Richiesta', route('student.direct-requests.purchased'));
});

Breadcrumbs::for('student.direct-requests.show', function (BreadcrumbTrail $trail, $id) {
    $request = LessonRequest::find($id);

    if ($request?->is_paid) {
        $trail->parent('student.direct-requests.purchased');
    } else {
        $trail->parent('student.direct-requests.index');
    }

    $trail->push('Visualizza Richiesta', route('student.direct-requests.show', $id));
});

Breadcrumbs::for('student.review', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Recensione', route('student.review'));
});
