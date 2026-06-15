@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Richieste studenti" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-table title="Elenco richieste" :requests="$lessonRequests"
            empty-text="Nessuna richiesta presente." />
    </x-ui.page-section>
@endsection
