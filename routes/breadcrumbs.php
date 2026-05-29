<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('admin.dashboard'));
});

Breadcrumbs::for('admin.account', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Account', route('admin.account'));
});

Breadcrumbs::for('admin.account.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account');
    $trail->push('Profile', route('admin.account.profile'));
});

Breadcrumbs::for('admin.account.credentials', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account');
    $trail->push('Credentials', route('admin.account.credentials'));
});

Breadcrumbs::for('admin.account.photo', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push('Photo', route('admin.account.photo'));
});

Breadcrumbs::for('admin.account.address', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push('Address', route('admin.account.address'));
});

Breadcrumbs::for('admin.account.certificates.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push('Certificates', route('admin.account.certificates.index'));
});

Breadcrumbs::for('admin.account.certificates.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.certificates.index');
    $trail->push('New certificate', route('admin.account.certificates.create'));
});

Breadcrumbs::for('admin.account.vat-number', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push('VAT number', route('admin.account.vat-number'));
});

Breadcrumbs::for('admin.teaching.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Teaching', route('admin.teaching.index'));
});

Breadcrumbs::for('admin.theme-areas.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push('Theme areas', route('admin.theme-areas.index'));
});

Breadcrumbs::for('admin.subjects.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push('Subjects', route('admin.subjects.index'));
});

Breadcrumbs::for('admin.courses.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push('New course', route('admin.courses.create'));
});

Breadcrumbs::for('admin.courses.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push('Courses', route('admin.courses.index'));
});

Breadcrumbs::for('admin.courses.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.courses.index');
    $trail->push('Edit course', route('admin.courses.edit', $id));
});

Breadcrumbs::for('admin.lessons.create', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.courses.edit', $id);
    $trail->push('New lesson', route('admin.lessons.create', $id));
});

Breadcrumbs::for('admin.lessons.edit', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('admin.courses.edit', $id_corso);
    $trail->push('Edit lesson', route('admin.lessons.edit', [
        'id_corso' => $id_corso,
        'id_lezione' => $id_lezione,
    ]));
});

Breadcrumbs::for('admin.exercises.create', function (BreadcrumbTrail $trail, $course) {
    $trail->parent('admin.courses.edit', $course);
    $trail->push('New exercise', route('admin.exercises.create', $course));
});

Breadcrumbs::for('admin.exercises.edit', function (BreadcrumbTrail $trail, $course, $exercise) {
    $trail->parent('admin.courses.edit', $course);
    $trail->push('Edit exercise', route('admin.exercises.edit', [
        'course' => $course,
        'exercise' => $exercise,
    ]));
});

Breadcrumbs::for('admin.students.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Students', route('admin.students.index'));
});

Breadcrumbs::for('admin.lesson-requests.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.students.index');
    $trail->push('Lesson requests', route('admin.lesson-requests.index'));
});

Breadcrumbs::for('admin.lesson-requests.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.lesson-requests.index');
    $trail->push('Lesson request', route('admin.lesson-requests.show', $id));
});

Breadcrumbs::for('admin.chats.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.students.index');
    $trail->push('Chats', route('admin.chats.index'));
});

Breadcrumbs::for('admin.chats.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.chats.index');
    $trail->push('Chat', route('admin.chats.show', $id));
});

Breadcrumbs::for('admin.sales.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Sales', route('admin.sales.index'));
});

Breadcrumbs::for('admin.orders.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.sales.index');
    $trail->push('Order', route('admin.orders.show', $id));
});

Breadcrumbs::for('admin.orders.invoice', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.orders.show', $id);
    $trail->push('Invoice', route('admin.orders.invoice', $id));
});

Breadcrumbs::for('admin.invoices.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Invoices', route('admin.invoices.index'));
});

Breadcrumbs::for('admin.invoices.extra', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.invoices.index');
    $trail->push('Extra invoice', route('admin.invoices.extra'));
});

Breadcrumbs::for('admin.invoices.created', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.invoices.extra');
    $trail->push('Created', route('admin.invoices.created'));
});

Breadcrumbs::for('admin.invoices.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.invoices.index');
    $trail->push('Invoice', route('admin.invoices.show', $id));
});

Breadcrumbs::for('student.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('student.dashboard'));
});

Breadcrumbs::for('student.account', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Account', route('student.account'));
});

Breadcrumbs::for('student.account.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('student.account');
    $trail->push('Profile', route('student.account.profile'));
});

Breadcrumbs::for('student.account.credentials', function (BreadcrumbTrail $trail) {
    $trail->parent('student.account');
    $trail->push('Credentials', route('student.account.credentials'));
});

Breadcrumbs::for('student.courses.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Courses', route('student.courses.index'));
});

Breadcrumbs::for('student.courses.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.courses.index');
    $trail->push('Course', route('student.courses.show', $id));
});

Breadcrumbs::for('student.lessons.show', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('student.courses.show', $id_corso);
    $trail->push('Lesson', route('student.lessons.show', [
        'id_corso' => $id_corso,
        'id_lezione' => $id_lezione,
    ]));
});

Breadcrumbs::for('student.exercises.show', function (BreadcrumbTrail $trail, $id_corso, $id_esercizio) {
    $trail->parent('student.courses.show', $id_corso);
    $trail->push('Exercise', route('student.exercises.show', [
        'id_corso' => $id_corso,
        'id_esercizio' => $id_esercizio,
    ]));
});

Breadcrumbs::for('student.orders.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Orders', route('student.orders.index'));
});

Breadcrumbs::for('student.orders.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.orders.index');
    $trail->push('Order', route('student.orders.show', $id));
});

Breadcrumbs::for('student.invoices.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Invoices', route('student.invoices.index'));
});

Breadcrumbs::for('student.invoices.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.invoices.index');
    $trail->push('Invoice', route('student.invoices.show', $id));
});

Breadcrumbs::for('student.invoice-sheets.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.invoices.index');
    $trail->push('Invoice sheet', route('student.invoice-sheets.show', $id));
});

Breadcrumbs::for('student.direct-requests.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Direct requests', route('student.direct-requests.index'));
});

Breadcrumbs::for('student.direct-requests.purchased', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Purchased requests', route('student.direct-requests.purchased'));
});

Breadcrumbs::for('student.direct-requests.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.direct-requests.index');
    $trail->push('Direct request', route('student.direct-requests.show', $id));
});

Breadcrumbs::for('student.review', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Review', route('student.review'));
});

Breadcrumbs::for('payment.extra', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Extra payment', route('payment.extra'));
});

Breadcrumbs::for('payment.pay', function (BreadcrumbTrail $trail) {
    $trail->parent('payment.extra');
    $trail->push('Pay', route('payment.pay'));
});

Breadcrumbs::for('payment.ok', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push('Payment ok', route('payment.ok'));
});

Breadcrumbs::for('theme-areas.index', function (BreadcrumbTrail $trail) {
    $trail->push('Theme areas', route('theme-areas.index'));
});

Breadcrumbs::for('subjects.index', function (BreadcrumbTrail $trail, $id_at) {
    $trail->parent('theme-areas.index');
    $trail->push('Subjects', route('subjects.index', $id_at));
});

Breadcrumbs::for('courses.index', function (BreadcrumbTrail $trail, $id_materia) {
    $trail->parent('theme-areas.index');
    $trail->push('Courses', route('courses.index', $id_materia));
});

Breadcrumbs::for('courses.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('theme-areas.index');
    $trail->push('Course', route('courses.show', $id));
});

Breadcrumbs::for('lessons.presentation', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('courses.show', $id_corso);
    $trail->push('Lesson presentation', route('lessons.presentation', [
        'id_corso' => $id_corso,
        'id_lezione' => $id_lezione,
    ]));
});

Breadcrumbs::for('lessons.show', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('courses.show', $id_corso);
    $trail->push('Lesson', route('lessons.show', [
        'id_corso' => $id_corso,
        'id_lezione' => $id_lezione,
    ]));
});

Breadcrumbs::for('exercises.trace', function (BreadcrumbTrail $trail, $id_corso, $id_esercizio) {
    $trail->parent('courses.show', $id_corso);
    $trail->push('Exercise trace', route('exercises.trace', [
        'id_corso' => $id_corso,
        'id_esercizio' => $id_esercizio,
    ]));
});
