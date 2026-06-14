@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Fattura" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

                <div class="text-start">
                    <h4 class="fw-bold mb-1">
                        Fattura Ordine #{{ $orderId }}
                    </h4>

                    <p class="text-muted mb-0">
                        Visualizzazione documento PDF
                    </p>
                </div>

                <div class="mt-3 mt-md-0">
                    <a href="/protected-files/{{ $invoice->file_path }}" target="_blank" class="btn btn-outline-primary px-4">
                        Apri in nuova scheda
                    </a>
                </div>

            </div>
            <x-ui.pdf-viewer :src="'/protected-files/' . $invoice->file_path" :title="'Fattura ordine #' . $orderId" />
        </x-ui.card>
    </x-ui.page-section>
@endsection
