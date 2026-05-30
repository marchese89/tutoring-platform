@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Dati Personali'" />
@endsection

@section('inner')
    <div class="container">

        <div class="row g-4">

            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-image fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">Modifica Foto</h4>

                        <p class="text-muted mb-4">
                            Gestione e aggiornamento dell’immagine profilo o contenuti fotografici.
                        </p>

                        <a href="{{ route('admin.account.photo') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-location-dot fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">Modifica Indirizzo</h4>

                        <p class="text-muted mb-4">
                            Aggiornamento dati di residenza o contatti geografici.
                        </p>

                        <a href="{{ route('admin.account.address') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-certificate fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">Modifica Certificati</h4>

                        <p class="text-muted mb-4">
                            Gestione dei certificati e documenti allegati.
                        </p>

                        <a href="{{ route('admin.account.certificates.index') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-file-invoice-dollar fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">Modifica Partita IVA</h4>

                        <p class="text-muted mb-4">
                            Aggiornamento dati fiscali e partita IVA.
                        </p>

                        <a href="{{ route('admin.account.vat-number') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
