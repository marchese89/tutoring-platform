@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Impostazioni Account'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item
                title="Modifica Dati Personali"
                text="Aggiorna informazioni anagrafiche, indirizzo e dati del profilo."
                :url="route('admin.account.profile')"
                icon="fa-solid fa-user"
            />

            <x-ui.card-item
                title="Modifica Credenziali"
                text="Gestisci email, password e sicurezza dell'account amministratore."
                :url="route('admin.account.credentials')"
                icon="fa-solid fa-shield-halved"
            />
        </div>
    </x-ui.page-section>
@endsection
