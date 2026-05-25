@extends('layouts.dashboard-admin')

@section('page-title')
    <div class="container">
        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem;">
                    Insegnamento
                </h2>

                <p class="text-muted mb-0">
                    Gestisci le aree tematiche, le materie e i corsi.
                </p>
            </div>
        </div>
    </div>
@endsection


@section('inner')
    <div class="container">

        <div class="row g-4">

            {{-- AREE TEMATICHE --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4 d-flex flex-column">

                        <div class="mb-4">
                            <i class="fa-solid fa-layer-group fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Aree Tematiche
                        </h4>

                        <p class="text-muted mb-4 flex-grow-1">
                            Gestione delle aree tematiche della piattaforma didattica.
                        </p>

                        <a href="{{ url('aree-tem') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

            {{-- MATERIE --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4 d-flex flex-column">

                        <div class="mb-4">
                            <i class="fa-solid fa-book fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Materie
                        </h4>

                        <p class="text-muted mb-4 flex-grow-1">
                            Organizzazione e gestione delle materie disponibili nei corsi.
                        </p>

                        <a href="{{ url('materie') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

            {{-- CORSI --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4 d-flex flex-column">

                        <div class="mb-4">
                            <i class="fa-solid fa-graduation-cap fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Nuovo Corso
                        </h4>

                        <p class="text-muted mb-4 flex-grow-1">
                            Creazione e configurazione di nuovi corsi didattici.
                        </p>

                        <a href="{{ url('nuovo-corso') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

            {{-- ELENCO CORSI --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4 d-flex flex-column">

                        <div class="mb-4">
                            <i class="fa-solid fa-list fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Elenco Corsi
                        </h4>

                        <p class="text-muted mb-4 flex-grow-1">
                            Visualizzazione e gestione dell’elenco completo dei corsi.
                        </p>

                        <a href="{{ url('elenco-corsi') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
