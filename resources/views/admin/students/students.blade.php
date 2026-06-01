@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Richieste Studenti'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item
                title="Richieste Studenti"
                text="Visualizza e gestisci tutte le richieste inviate dagli studenti."
                :url="route('admin.lesson-requests.index')"
                column-class="col-lg-5"
            />

            <x-ui.card-item
                title="Chat Studenti"
                text="Accedi alle conversazioni e comunica con gli studenti."
                :url="route('admin.chats.index')"
                column-class="col-lg-5"
            />
        </div>
    </x-ui.page-section>
@endsection
