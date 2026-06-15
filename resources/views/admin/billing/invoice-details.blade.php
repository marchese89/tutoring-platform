@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Visualizza fattura" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.pdf-document-card :src="route('protected-files.show', ['path' => $invoice->file_path], false)"
            title="Documento fattura" />
    </x-ui.page-section>
@endsection
