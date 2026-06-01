@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Impostazioni Account'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item
                title="Dati Personali"
                text="Modifica le informazioni personali associate al profilo."
                :url="route('student.account.profile')"
                icon="fa-solid fa-id-card"
            />

            <x-ui.card-item
                title="Credenziali"
                text="Aggiorna password e dati di accesso dell'account."
                :url="route('student.account.credentials')"
                icon="fa-solid fa-key"
            />
        </div>
    </x-ui.page-section>
@endsection
