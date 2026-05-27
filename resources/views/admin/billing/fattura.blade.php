@extends('layouts.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Fattura'" />
@endsection

@section('inner')
    <div class="container py-4">

        <div class="row justify-content-center">
            <div class="col-12">

                <x-ui.card>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

                        <div class="text-start">
                            <h4 class="fw-bold mb-1">
                                Fattura Ordine #{{ request('id') }}
                            </h4>

                            <p class="text-muted mb-0">
                                Visualizzazione documento PDF
                            </p>
                        </div>

                        <div class="mt-3 mt-md-0">
                            <a href="/protected_file/{{ $invoice->path }}" target="_blank"
                                class="btn btn-outline-primary px-4">
                                Apri in nuova scheda
                            </a>
                        </div>

                    </div>

                    <div class="border rounded overflow-hidden bg-body-tertiary">

                        <iframe width="100%" height="850px" style="border: none"
                            src="/protected_file/{{ $invoice->path }}#view=FitH">
                        </iframe>

                    </div>

                </x-ui.card>

            </div>
        </div>

    </div>
@endsection
