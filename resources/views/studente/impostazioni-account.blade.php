@extends('layouts.dashboard-studente')

@section('page-title')
    <x-ui.section-header :title="'Impostazioni Account'" />
@endsection

@section('inner')
    <div class="container py-4">

        {{-- CARDS --}} <div class="row g-4">

            {{-- DATI PERSONALI --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-id-card fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Dati Personali
                        </h4>

                        <p class="text-muted mb-4">
                            Modifica le informazioni personali associate al profilo.
                        </p>

                        <a href="{{ url('mod-dati-pers-stud') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

            {{-- CREDENZIALI --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-key fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Credenziali
                        </h4>

                        <p class="text-muted mb-4">
                            Aggiorna password e dati di accesso dell’account.
                        </p>

                        <a href="{{ url('mod-cred-stud') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
