<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;


Breadcrumbs::for('dashboard-admin', function (BreadcrumbTrail $trail) {

    $trail->push('Dashboard', route('dashboard-admin'));
});
Breadcrumbs::for('imp-account', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard-admin');
    $trail->push('Impostazioni account', route('imp-account'));
});
Breadcrumbs::for('mod-dati-pers', function (BreadcrumbTrail $trail) {
    $trail->parent('imp-account');
    $trail->push('Modifica dati personali', route('mod-dati-pers'));
});
Breadcrumbs::for('mod-cred', function (BreadcrumbTrail $trail) {
    $trail->parent('imp-account');
    $trail->push('Modifica credenziali', route('mod-cred'));
});
Breadcrumbs::for('mod-foto-admin', function (BreadcrumbTrail $trail) {
    $trail->parent('mod-dati-pers');
    $trail->push('Modifica foto', route('mod-foto-admin'));
});
Breadcrumbs::for('mod-indirizzo-admin', function (BreadcrumbTrail $trail) {
    $trail->parent('mod-dati-pers');
    $trail->push('Modifica indirizzo', route('mod-indirizzo-admin'));
});
Breadcrumbs::for('mod-certif', function (BreadcrumbTrail $trail) {
    $trail->parent('mod-dati-pers');
    $trail->push('Modifica certificati', route('mod-certif'));
});

Breadcrumbs::for('aggiungi-certif', function (BreadcrumbTrail $trail) {
    $trail->parent('mod-certif');
    $trail->push('Aggiungi certificato', route('aggiungi-certif'));
});

Breadcrumbs::for('extra-fattura', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard-admin');
    $trail->push('Fattura extra', route('extra-fattura'));
});

Breadcrumbs::for('fatture', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard-admin');
    $trail->push('Fatture', route('fatture'));
});

Breadcrumbs::for('visualizza-fattura', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('fatture');
    $trail->push('Visualizza fattura', route('visualizza-fattura', $id));
});

Breadcrumbs::for('vendite', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard-admin');
    $trail->push('Vendite', route('vendite'));
});

Breadcrumbs::for('mod-part-iva', function (BreadcrumbTrail $trail) {
    $trail->parent('mod-dati-pers');
    $trail->push('Modifica Partita IVA', route('mod-part-iva'));
});

Breadcrumbs::for('insegnamento', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard-admin');
    $trail->push('Insegnamento', route('insegnamento'));
});
Breadcrumbs::for('nuovo-corso', function (BreadcrumbTrail $trail) {
    $trail->parent('insegnamento');
    $trail->push('Nuovo corso', route('nuovo-corso'));
});
Breadcrumbs::for('aree-tem', function (BreadcrumbTrail $trail) {
    $trail->parent('insegnamento');
    $trail->push('Aree tematiche', route('aree-tem'));
});

Breadcrumbs::for('admin.teaching.materie', function (BreadcrumbTrail $trail) {
    $trail->parent('insegnamento');
    $trail->push('Materie', route('admin.teaching.materie'));
});
Breadcrumbs::for('elenco-corsi', function (BreadcrumbTrail $trail) {
    $trail->parent('insegnamento');
    $trail->push('Elenco corsi', route('elenco-corsi'));
});

Breadcrumbs::for('modifica-dettagli-corso', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('elenco-corsi');
    $trail->push('Modifica dettagli corso', route('modifica-dettagli-corso', $id));
});

Breadcrumbs::for('nuova-lezione', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('modifica-dettagli-corso', $id);
    $trail->push('Nuova lezione', route('nuova-lezione', $id));
});

Breadcrumbs::for('studenti', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard-admin');
    $trail->push('Studenti', route('studenti'));
});

Breadcrumbs::for('richieste-studenti', function (BreadcrumbTrail $trail) {
    $trail->parent('studenti');
    $trail->push('Richieste studenti', route('richieste-studenti'));
});

Breadcrumbs::for('chat-studenti', function (BreadcrumbTrail $trail) {
    $trail->parent('studenti');
    $trail->push('Chat studenti', route('chat-studenti'));
});

Breadcrumbs::for('visualizza-richiesta', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('richieste-studenti');
    $trail->push('Visualizza richiesta', route('visualizza-richiesta', $id));
});

Breadcrumbs::for('visualizza-chat', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('chat-studenti');
    $trail->push('Visualizza chat', route('visualizza-chat', $id));
});

Breadcrumbs::for('dashboard-studente', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard-studente'));
});

Breadcrumbs::for('imp-account-studente', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard-studente');
    $trail->push('Impostazioni account', route('imp-account-studente'));
});

Breadcrumbs::for('mod-dati-pers-stud', function (BreadcrumbTrail $trail) {
    $trail->parent('imp-account-studente');
    $trail->push('Modifica dati personali', route('mod-dati-pers-stud'));
});

Breadcrumbs::for('mod-cred-stud', function (BreadcrumbTrail $trail) {
    $trail->parent('imp-account-studente');
    $trail->push('Modifica credenziali', route('mod-cred-stud'));
});

Breadcrumbs::for('studente.corsi', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard-studente');
    $trail->push('Corsi', route('studente.corsi'));
});

Breadcrumbs::for('course', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('corsi');
    $trail->push('Corso', route('course', $id));
});

Breadcrumbs::for('lezione', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('course', $id_corso);
    $trail->push('Lezione', route('lezione', ['id_corso' => $id_corso, 'id_lezione' => $id_lezione]));
});

Breadcrumbs::for('esercizio', function (BreadcrumbTrail $trail, $id_corso, $id_esercizio) {
    $trail->parent('course', $id_corso);
    $trail->push('Esercizio', route('esercizio', ['id_corso' => $id_corso, 'id_esercizio' => $id_esercizio]));
});

Breadcrumbs::for('aree-tematiche', function (BreadcrumbTrail $trail) {
    $trail->push('Aree tematiche', route('aree-tematiche'));
});

Breadcrumbs::for('materie', function (BreadcrumbTrail $trail, $id_at) {
    $trail->parent('aree-tematiche');
    $trail->push('Materie', route('materie', $id_at));
});

Breadcrumbs::for('corsi', function (BreadcrumbTrail $trail, $id_materia) {
    $trail->parent('materie', $id_materia);
    $trail->push('Corsi', route('corsi', $id_materia));
});

Breadcrumbs::for('corso', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('corsi', $id);
    $trail->push('Corso', route('corso', $id));
});

Breadcrumbs::for('presentazione-lezione', function (BreadcrumbTrail $trail, $id_lezione, $id_corso) {
    $trail->parent('corso', $id_corso);
    $trail->push('Presentazione lezione', route('presentazione-lezione', ['id_lezione' => $id_lezione, 'id_corso' => $id_corso]));
});

Breadcrumbs::for('traccia-esercizio', function (BreadcrumbTrail $trail, $id_esercizio, $id_corso) {
    $trail->parent('corso', $id_corso);
    $trail->push('Traccia esercizio', route('traccia-esercizio', ['id_esercizio' => $id_esercizio, 'id_corso' => $id_corso]));
});

Breadcrumbs::for('modifica-lezione', function (BreadcrumbTrail $trail, $id_corso, $id_lezione) {
    $trail->parent('modifica-dettagli-corso', $id_corso);
    $trail->push('Modifica lezione', route('modifica-lezione', ['id_corso' => $id_corso, 'id_lezione' => $id_lezione]));
});

Breadcrumbs::for('visualizza-lezione', function (BreadcrumbTrail $trail, $id_lezione, $id_corso) {
    $trail->parent('corso', $id_corso);
    $trail->push('Visualizza lezione', route('visualizza-lezione', ['id_lezione' => $id_lezione, 'id_corso' => $id_corso,]));
});

Breadcrumbs::for('nuovo-esercizio', function (BreadcrumbTrail $trail, $id_corso) {
    $trail->parent('modifica-dettagli-corso', $id_corso);
    $trail->push('Nuovo esercizio', route('nuovo-esercizio', $id_corso));
});

Breadcrumbs::for('modifica-esercizio', function (BreadcrumbTrail $trail, $course, $exercise) {
    $trail->parent('modifica-dettagli-corso', $course);
    $trail->push('Modifica esercizio', route('modifica-esercizio', ['course' => $course, 'exercise' => $exercise]));
});
