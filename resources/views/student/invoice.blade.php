@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Fattura'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">
                        {{ $title }}
                    </h4>

                    <p class="text-muted mb-0">
                        Visualizzazione documento PDF
                    </p>
                </div>

                <x-ui.primary-button href="{{ $backUrl }}" size="sm">
                    Indietro
                </x-ui.primary-button>
            </div>

            <x-ui.pdf-viewer :src="'/protected-files/' . $invoice->file_path" :title="$title" />
        </x-ui.card>
    </x-ui.page-section>
@endsection
