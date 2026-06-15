<?php

use App\Models\Course;
use App\Models\LessonRequest;
use App\Models\Subject;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Public

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push(__('breadcrumbs.home'), route('home'));
});

Breadcrumbs::for('theme-areas.index', function (BreadcrumbTrail $trail) {
    $trail->push(__('breadcrumbs.theme_areas'), route('theme-areas.index'));
});

Breadcrumbs::for('subjects.index', function (BreadcrumbTrail $trail, $themeArea) {
    $trail->parent('theme-areas.index');
    $trail->push(__('breadcrumbs.subjects'), route('subjects.index', $themeArea));
});

Breadcrumbs::for('courses.index', function (BreadcrumbTrail $trail, $subjectId) {
    $subject = Subject::find($subjectId);

    if ($subject) {
        $trail->parent('subjects.index', $subject->theme_area_id);
    } else {
        $trail->parent('theme-areas.index');
    }

    $trail->push(__('breadcrumbs.courses'), route('courses.index', $subjectId));
});

Breadcrumbs::for('courses.show', function (BreadcrumbTrail $trail, $id) {
    $course = Course::find($id);

    if ($course) {
        $trail->parent('courses.index', $course->subject_id);
    } else {
        $trail->parent('theme-areas.index');
    }

    $trail->push(__('breadcrumbs.course'), route('courses.show', $id));
});

Breadcrumbs::for('lessons.presentation', function (BreadcrumbTrail $trail, $course, $lesson) {
    $trail->parent('courses.show', $course);
    $trail->push(__('breadcrumbs.lesson_presentation'), route('lessons.presentation', [
        'course' => $course,
        'lesson' => $lesson,
    ]));
});

Breadcrumbs::for('lessons.show', function (BreadcrumbTrail $trail, $course, $lesson) {
    $trail->parent('courses.show', $course);
    $trail->push(__('breadcrumbs.view_lesson'), route('lessons.show', [
        'course' => $course,
        'lesson' => $lesson,
    ]));
});

Breadcrumbs::for('exercises.trace', function (BreadcrumbTrail $trail, $course, $exercise) {
    $trail->parent('courses.show', $course);
    $trail->push(__('breadcrumbs.exercise_trace'), route('exercises.trace', [
        'course' => $course,
        'exercise' => $exercise,
    ]));
});

// Admin - dashboard

Breadcrumbs::for('admin.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('breadcrumbs.dashboard'), route('admin.dashboard'));
});

// Admin - account

Breadcrumbs::for('admin.account', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('breadcrumbs.account_settings'), route('admin.account'));
});

Breadcrumbs::for('admin.account.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account');
    $trail->push(__('breadcrumbs.personal_details'), route('admin.account.profile'));
});

Breadcrumbs::for('admin.account.credentials', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account');
    $trail->push(__('breadcrumbs.credentials'), route('admin.account.credentials'));
});

Breadcrumbs::for('admin.account.photo', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push(__('breadcrumbs.photo'), route('admin.account.photo'));
});

Breadcrumbs::for('admin.account.address', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push(__('breadcrumbs.address'), route('admin.account.address'));
});

Breadcrumbs::for('admin.account.certificates.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push(__('breadcrumbs.certificates'), route('admin.account.certificates.index'));
});

Breadcrumbs::for('admin.account.certificates.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.certificates.index');
    $trail->push(__('breadcrumbs.add_certificate'), route('admin.account.certificates.create'));
});

Breadcrumbs::for('admin.account.vat-number', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.account.profile');
    $trail->push(__('breadcrumbs.vat_number'), route('admin.account.vat-number'));
});

// Admin - teaching

Breadcrumbs::for('admin.teaching.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('breadcrumbs.teaching'), route('admin.teaching.index'));
});

Breadcrumbs::for('admin.theme-areas.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push(__('breadcrumbs.theme_areas'), route('admin.theme-areas.index'));
});

Breadcrumbs::for('admin.subjects.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push(__('breadcrumbs.subjects'), route('admin.subjects.index'));
});

Breadcrumbs::for('admin.courses.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push(__('breadcrumbs.new_course'), route('admin.courses.create'));
});

Breadcrumbs::for('admin.courses.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.teaching.index');
    $trail->push(__('breadcrumbs.course_list'), route('admin.courses.index'));
});

Breadcrumbs::for('admin.courses.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.courses.index');
    $trail->push(__('breadcrumbs.edit_course'), route('admin.courses.edit', $id));
});

Breadcrumbs::for('admin.lessons.create', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.courses.edit', $id);
    $trail->push(__('breadcrumbs.new_lesson'), route('admin.lessons.create', $id));
});

Breadcrumbs::for('admin.lessons.edit', function (BreadcrumbTrail $trail, $course, $lesson) {
    $trail->parent('admin.courses.edit', $course);
    $trail->push(__('breadcrumbs.edit_lesson'), route('admin.lessons.edit', [
        'course' => $course,
        'lesson' => $lesson,
    ]));
});

Breadcrumbs::for('admin.exercises.create', function (BreadcrumbTrail $trail, $course) {
    $trail->parent('admin.courses.edit', $course);
    $trail->push(__('breadcrumbs.new_exercise'), route('admin.exercises.create', $course));
});

Breadcrumbs::for('admin.exercises.edit', function (BreadcrumbTrail $trail, $course, $exercise) {
    $trail->parent('admin.courses.edit', $course);
    $trail->push(__('breadcrumbs.edit_exercise'), route('admin.exercises.edit', [
        'course' => $course,
        'exercise' => $exercise,
    ]));
});

// Admin - students

Breadcrumbs::for('admin.students.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('breadcrumbs.students'), route('admin.students.index'));
});

Breadcrumbs::for('admin.lesson-requests.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.students.index');
    $trail->push(__('breadcrumbs.student_requests'), route('admin.lesson-requests.index'));
});

Breadcrumbs::for('admin.lesson-requests.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.lesson-requests.index');
    $trail->push(__('breadcrumbs.view_request'), route('admin.lesson-requests.show', $id));
});

Breadcrumbs::for('admin.chats.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.students.index');
    $trail->push(__('breadcrumbs.student_chats'), route('admin.chats.index'));
});

Breadcrumbs::for('admin.chats.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.chats.index');
    $trail->push(__('breadcrumbs.view_chat'), route('admin.chats.show', $id));
});

// Admin - billing

Breadcrumbs::for('admin.sales.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('breadcrumbs.sales'), route('admin.sales.index'));
});

Breadcrumbs::for('admin.orders.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.sales.index');
    $trail->push(__('breadcrumbs.order'), route('admin.orders.show', $id));
});

Breadcrumbs::for('admin.orders.invoice', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.orders.show', $id);
    $trail->push(__('breadcrumbs.invoice'), route('admin.orders.invoice', $id));
});

Breadcrumbs::for('admin.invoices.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('breadcrumbs.invoices'), route('admin.invoices.index'));
});

Breadcrumbs::for('admin.invoices.extra', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('breadcrumbs.extra_invoice'), route('admin.invoices.extra'));
});

Breadcrumbs::for('admin.invoices.created', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.invoices.extra');
    $trail->push(__('breadcrumbs.invoice_created'), route('admin.invoices.created'));
});

Breadcrumbs::for('admin.invoices.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('admin.invoices.index');
    $trail->push(__('breadcrumbs.view_invoice'), route('admin.invoices.show', $id));
});

// Student - dashboard

Breadcrumbs::for('student.dashboard', function (BreadcrumbTrail $trail) {
    $trail->push(__('breadcrumbs.dashboard'), route('student.dashboard'));
});

// Student - account

Breadcrumbs::for('student.account', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.account_settings'), route('student.account'));
});

Breadcrumbs::for('student.account.profile', function (BreadcrumbTrail $trail) {
    $trail->parent('student.account');
    $trail->push(__('breadcrumbs.personal_details'), route('student.account.profile'));
});

Breadcrumbs::for('student.account.credentials', function (BreadcrumbTrail $trail) {
    $trail->parent('student.account');
    $trail->push(__('breadcrumbs.credentials'), route('student.account.credentials'));
});

// Student - courses

Breadcrumbs::for('student.courses.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.courses'), route('student.courses.index'));
});

Breadcrumbs::for('student.courses.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.courses.index');
    $trail->push(__('breadcrumbs.course'), route('student.courses.show', $id));
});

Breadcrumbs::for('student.lessons.show', function (BreadcrumbTrail $trail, $course, $lesson) {
    $trail->parent('student.courses.show', $course);
    $trail->push(__('breadcrumbs.lesson'), route('student.lessons.show', [
        'course' => $course,
        'lesson' => $lesson,
    ]));
});

Breadcrumbs::for('student.exercises.show', function (BreadcrumbTrail $trail, $course, $exercise) {
    $trail->parent('student.courses.show', $course);
    $trail->push(__('breadcrumbs.exercise'), route('student.exercises.show', [
        'course' => $course,
        'exercise' => $exercise,
    ]));
});

// Student - orders and payments

Breadcrumbs::for('student.orders.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.orders'), route('student.orders.index'));
});

Breadcrumbs::for('student.orders.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.orders.index');
    $trail->push(__('breadcrumbs.order'), route('student.orders.show', $id));
});

Breadcrumbs::for('student.invoices.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.invoices'), route('student.invoices.index'));
});

Breadcrumbs::for('student.invoices.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('student.invoices.index');
    $trail->push(__('breadcrumbs.invoice'), route('student.invoices.show', $id));
});

Breadcrumbs::for('payment.extra', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.extra_payment'), route('payment.extra'));
});

Breadcrumbs::for('payment.pay', function (BreadcrumbTrail $trail) {
    $trail->parent('payment.extra');
    $trail->push(__('breadcrumbs.purchase'), route('payment.pay'));
});

Breadcrumbs::for('payment.ok', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.invoice_created'), route('payment.ok'));
});

// Student - requests and review

Breadcrumbs::for('student.direct-requests.index', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.direct_requests'), route('student.direct-requests.index'));
});

Breadcrumbs::for('student.direct-requests.purchased', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.requested_lessons'), route('student.direct-requests.purchased'));
});

Breadcrumbs::for('student.direct-requests.show', function (BreadcrumbTrail $trail, $id) {
    $request = LessonRequest::find($id);

    if ($request?->is_paid) {
        $trail->parent('student.direct-requests.purchased');
    } else {
        $trail->parent('student.direct-requests.index');
    }

    $trail->push(__('breadcrumbs.view_request'), route('student.direct-requests.show', $id));
});

Breadcrumbs::for('student.review', function (BreadcrumbTrail $trail) {
    $trail->parent('student.dashboard');
    $trail->push(__('breadcrumbs.review'), route('student.review'));
});
