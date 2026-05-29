@extends('layouts.layout-bootstrap')

@section('content')
    <main>
        @if (!Request::is('student/dashboard'))
            @yield('page-title')
            <div class="container">
                {{ Breadcrumbs::render() }}
            </div>
            @yield('inner')
        @else
            <x-ui.section-header :title="'Dashboard Studente'" />

            <div class="container">
                <div class="row g-4">
                    <x-ui.card-item title="Impostazioni Account" text="Modifica dati personali e password dell'account."
                        :url="route('student.account')" />

                    <x-ui.card-item title="Corsi Acquistati" text="Lezioni ed esercizi dei corsi acquistati."
                        :url="route('student.courses.index')" />

                    <x-ui.card-item title="Richieste Dirette" text="Storico richieste e relative informazioni economiche."
                        :url="route('student.direct-requests.index')" />

                    <x-ui.card-item title="Lezioni su Richiesta" text="Materiali e contenuti acquistati su richiesta."
                        :url="route('student.direct-requests.purchased')" />

                    <x-ui.card-item title="Ordini" text="Storico e stato degli ordini effettuati."
                        :url="route('student.orders.index')" />

                    <x-ui.card-item title="Recensione" text="Valutazione del servizio e feedback."
                        :url="route('student.review')" />

                    <x-ui.card-item title="Pagamento Extra" text="Pagamento lezioni private e servizi aggiuntivi."
                        :url="route('payment.extra')" />

                    <x-ui.card-item title="Fatture" text="Storico fatture dei pagamenti extra."
                        :url="route('student.invoices.index')" />
                </div>
            </div>
        @endif
    </main>
@endsection
