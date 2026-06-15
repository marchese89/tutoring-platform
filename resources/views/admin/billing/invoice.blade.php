@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Fattura" />
@endsection

@section('inner')
    <x-ui.page-section>
        @php
            $documentUrl = route('protected-files.show', ['path' => $invoice->file_path], false);
        @endphp

        <x-ui.pdf-document-card :src="$documentUrl" :title="'Fattura Ordine #' . $orderId"
            :viewer-title="'Fattura ordine #' . $orderId" :action-url="$documentUrl" action-label="Apri in una nuova scheda"
            action-target="_blank" />
    </x-ui.page-section>
@endsection
