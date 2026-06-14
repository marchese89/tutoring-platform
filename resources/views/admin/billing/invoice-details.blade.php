@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Visualizza fattura" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card>
            <h4 class="fw-bold mb-1">
                Documento fattura
            </h4>

            <p class="text-muted mb-4">
                Visualizzazione documento PDF
            </p>

            <x-ui.pdf-viewer :src="'/protected-files/' . $invoice->file_path" title="Documento fattura" />
        </x-ui.card>
    </x-ui.page-section>
@endsection
