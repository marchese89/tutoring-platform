@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Richieste Studenti'" />
@endsection

@section('inner')
    <div class="container">

        <div class="row g-4">

            {{-- RICHIESTE STUDENTI --}}
            <div class="col-lg-5">

                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column">

                        <h4 class="fw-bold mb-3">
                            Richieste Studenti
                        </h4>

                        <p class="text-muted mb-4">
                            Visualizza e gestisci tutte le richieste inviate dagli studenti.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('admin.lesson-requests.index') }}" class="btn btn-primary rounded-pill px-4">
                                Accedi
                            </a>
                        </div>

                    </div>
                </div>

            </div>

            {{-- CHAT STUDENTI --}}
            <div class="col-lg-5">

                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 d-flex flex-column">

                        <h4 class="fw-bold mb-3">
                            Chat Studenti
                        </h4>

                        <p class="text-muted mb-4">
                            Accedi alle conversazioni e comunica con gli studenti.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('admin.chats.index') }}" class="btn btn-primary rounded-pill px-4">
                                Accedi
                            </a>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection
