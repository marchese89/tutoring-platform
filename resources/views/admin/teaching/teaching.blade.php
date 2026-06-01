@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Insegnamento'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item
                title="Aree Tematiche"
                text="Gestione delle aree tematiche della piattaforma didattica."
                :url="route('admin.theme-areas.index')"
                icon="fa-solid fa-layer-group"
            />

            <x-ui.card-item
                title="Materie"
                text="Organizzazione e gestione delle materie disponibili nei corsi."
                :url="route('admin.subjects.index')"
                icon="fa-solid fa-book"
            />

            <x-ui.card-item
                title="Nuovo Corso"
                text="Creazione e configurazione di nuovi corsi didattici."
                :url="route('admin.courses.create')"
                icon="fa-solid fa-graduation-cap"
            />

            <x-ui.card-item
                title="Elenco Corsi"
                text="Visualizzazione e gestione dell'elenco completo dei corsi."
                :url="route('admin.courses.index')"
                icon="fa-solid fa-list"
            />
        </div>
    </x-ui.page-section>
@endsection
