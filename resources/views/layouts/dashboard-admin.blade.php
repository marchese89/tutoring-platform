@extends('layouts.layout-bootstrap')

@section('content')
    @if (!Request::is('dashboard-admin'))
        @yield('page-title')

        <div class="container">
            {{ Breadcrumbs::render() }}
        </div>

        @yield('inner')
    @else
        {{-- HEADER --}}
        <x-ui.section-header :title="'Dashboard Admin'" />
        <div class="container">

            {{-- CARDS --}}
            <div class="row g-4">

                <x-ui.card-item title="Impostazioni Account"
                    text="Modifica dati personali, email e password dell'account amministratore." url="imp-account" />

                <x-ui.card-item title="Insegnamento" text="Gestione aree tematiche, materie, corsi, lezioni ed esercizi."
                    url="insegnamento" />

                <x-ui.card-item title="Studenti" text="Richieste studenti, gestione chat e monitoraggio attività."
                    url="studenti" />

                <x-ui.card-item title="Vendite" text="Controllo ordini, statistiche e guadagni mensili e totali."
                    url="vendite" />

                <x-ui.card-item title="Fattura Extra" text="Creazione di fatture personalizzate per attività esterne."
                    url="extra-fattura" />

                <x-ui.card-item title="Elenco Fatture" text="Archivio completo delle fatture emesse dalla piattaforma."
                    url="fatture" />

            </div>

        </div>
    @endif
@endsection
