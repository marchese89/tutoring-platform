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

                    <x-ui.card-item title="Corsi Acquistati" text="Lezioni ed esercizi dei corsi acquistati."
                        url="studente.corsi" />

                    {{-- RICHIESTE DIRETTE --}}


                    <x-ui.card-item title="Richieste Dirette" text="Storico richieste e relative informazioni economiche."
                        url="richieste-dirette" />

                    {{-- LEZIONI SU RICHIESTA --}}

                    <x-ui.card-item title="Lezioni su Richiesta" text="Materiali e contenuti acquistati su richiesta."
                        url="richieste-dirette-acquistate" />

                    {{-- ORDINI --}}

                    <x-ui.card-item title="Ordini" text="Storico e stato degli ordini effettuati." url="ordini" />

                    {{-- RECENSIONI --}}
                    <div class="col-xl-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-body p-4">

                                <div class="mb-4">
                                    <i class="fa-solid fa-star fa-2x text-primary"></i>
                                </div>

                                <h4 class="fw-bold mb-3">
                                    Recensione
                                </h4>

                                <p class="text-muted mb-4">
                                    Valutazione del servizio e feedback.
                                </p>

                                <a href="{{ url('recensione') }}" class="btn btn-primary rounded-pill px-4">
                                    Accedi
                                </a>

                            </div>
                        </div>
                    </div>

                    {{-- PAGAMENTI --}}
                    <div class="col-xl-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-body p-4">

                                <div class="mb-4">
                                    <i class="fa-solid fa-credit-card fa-2x text-primary"></i>
                                </div>

                                <h4 class="fw-bold mb-3">
                                    Pagamento Extra
                                </h4>

                                <p class="text-muted mb-4">
                                    Pagamento lezioni private e servizi aggiuntivi.
                                </p>

                                <a href="{{ url('/payment/extra') }}" class="btn btn-primary rounded-pill px-4">
                                    Accedi
                                </a>

                            </div>
                        </div>
                    </div>

                    {{-- FATTURE --}}
                    <div class="col-xl-4 col-md-6">
                        <div class="card border-0 shadow-sm h-100 rounded-4">
                            <div class="card-body p-4">

                                <div class="mb-4">
                                    <i class="fa-solid fa-file-invoice fa-2x text-primary"></i>
                                </div>

                                <h4 class="fw-bold mb-3">
                                    Fatture
                                </h4>

                                <p class="text-muted mb-4">
                                    Storico fatture dei pagamenti extra.
                                </p>

                                <a href="{{ url('fatture-studente') }}" class="btn btn-primary rounded-pill px-4">
                                    Accedi
                                </a>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        @endif

    </main>
@endsection
