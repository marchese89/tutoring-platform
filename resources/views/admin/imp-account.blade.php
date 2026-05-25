@extends('admin.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Impostazioni Account'" />
@endsection

@section('inner')
    <div class="container">

        <div class="row g-4">

            {{-- DATI PERSONALI --}}
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 rounded-4">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-user fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Modifica Dati Personali
                        </h4>

                        <p class="text-muted mb-4">
                            Aggiorna informazioni anagrafiche, indirizzo e dati del profilo.
                        </p>

                        <a href="{{ url('mod-dati-pers') }}" class="btn btn-primary rounded-pill px-4">
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
                            <i class="fa-solid fa-shield-halved fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Modifica Credenziali
                        </h4>

                        <p class="text-muted mb-4">
                            Gestisci email, password e sicurezza dell’account amministratore.
                        </p>

                        <a href="{{ url('mod-cred') }}" class="btn btn-primary rounded-pill px-4">
                            Accedi
                        </a>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
