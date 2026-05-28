@extends('layouts.layout-bootstrap')

@section('content')
    <main>

        @if (!Request::is('dashboard-studente'))
            @yield('page-title')
            <div class="container">
                {{ Breadcrumbs::render() }}
            </div>
            @yield('inner')
        @else
            {{-- HEADER --}}
            <x-ui.section-header :title="'Dashboard Studente'" />
            <div class="container">



                {{-- CARDS --}}
                <div class="row g-4">

                    {{-- ACCOUNT --}}
                    <x-ui.card-item title="Impostazioni Account" text="Modifica dati personali e password dell’account."
                        url="imp-account-studente" />

                    {{-- CORSI --}}

                    <x-ui.card-item title="Corsi Acquistati" text="Lezioni ed esercizi dei corsi acquistati." url="corsi" />

                    {{-- RICHIESTE DIRETTE --}}


                    <x-ui.card-item title="Richieste Dirette" text="Storico richieste e relative informazioni economiche."
                        url="richieste-dirette" />

                    {{-- LEZIONI SU RICHIESTA --}}

                    <x-ui.card-item title="Lezioni su Richiesta" text="Materiali e contenuti acquistati su richiesta."
                        url="richieste-dirette-acquistate" />

                    {{-- ORDINI --}}

                    <x-ui.card-item title="Ordini" text="Storico e stato degli ordini effettuati." url="ordini" />

                    {{-- RECENSIONI --}}
                    <x-ui.card-item title="Recensione" text="Valutazione del servizio e feedback." url="recensione" />

                    {{-- PAGAMENTI --}}

                    <x-ui.card-item title="Pagamento Extra" text="Pagamento lezioni private e servizi aggiuntivi."
                        url="payment/extra" />

                    {{-- FATTURE --}}

                    <x-ui.card-item title="Fatture" text="Storico fatture dei pagamenti extra." url="fatture-studente" />

                </div>

            </div>
        @endif

    </main>
@endsection
