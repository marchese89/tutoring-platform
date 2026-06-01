@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Dati Personali'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item
                title="Modifica Foto"
                text="Gestione e aggiornamento dell'immagine profilo o contenuti fotografici."
                :url="route('admin.account.photo')"
                icon="fa-solid fa-image"
            />

            <x-ui.card-item
                title="Modifica Indirizzo"
                text="Aggiornamento dati di residenza o contatti geografici."
                :url="route('admin.account.address')"
                icon="fa-solid fa-location-dot"
            />

            <x-ui.card-item
                title="Modifica Certificati"
                text="Gestione dei certificati e documenti allegati."
                :url="route('admin.account.certificates.index')"
                icon="fa-solid fa-certificate"
            />

            <x-ui.card-item
                title="Modifica Partita IVA"
                text="Aggiornamento dati fiscali e partita IVA."
                :url="route('admin.account.vat-number')"
                icon="fa-solid fa-file-invoice-dollar"
            />
        </div>
    </x-ui.page-section>
@endsection
