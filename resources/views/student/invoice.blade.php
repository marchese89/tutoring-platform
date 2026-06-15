@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Fattura" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.pdf-document-card :src="route('protected-files.show', ['path' => $invoice->file_path], false)" :title="$title"
            :action-url="$backUrl" action-label="Indietro" />
    </x-ui.page-section>
@endsection
