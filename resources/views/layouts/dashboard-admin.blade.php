@extends('layouts.layout-bootstrap')

@section('content')
    @if (!Request::is('admin/dashboard'))
        @yield('page-title')

        <div class="container">
            {{ Breadcrumbs::render() }}
        </div>

        @yield('inner')
    @else
        <x-ui.section-header :title="'Dashboard Admin'" />
        <div class="container">
            <div class="row g-4">
                <x-ui.card-item title="Impostazioni Account"
                    text="Modifica dati personali, email e password dell'account amministratore."
                    :url="route('admin.account')" />

                <x-ui.card-item title="Insegnamento"
                    text="Gestione aree tematiche, materie, corsi, lezioni ed esercizi."
                    :url="route('admin.teaching.index')" />

                <x-ui.card-item title="Studenti"
                    text="Richieste studenti, gestione chat e monitoraggio attivita."
                    :url="route('admin.students.index')" />

                <x-ui.card-item title="Vendite"
                    text="Controllo ordini, statistiche e guadagni mensili e totali."
                    :url="route('admin.sales.index')" />

                <x-ui.card-item title="Fattura Extra"
                    text="Creazione di fatture personalizzate per attivita esterne."
                    :url="route('admin.invoices.extra')" />

                <x-ui.card-item title="Elenco Fatture"
                    text="Archivio completo delle fatture emesse dalla piattaforma."
                    :url="route('admin.invoices.index')" />
            </div>
        </div>
    @endif
@endsection
