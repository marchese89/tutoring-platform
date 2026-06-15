@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Lezioni su richiesta" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-table title="Lezioni acquistate" :requests="$purchasedDirectRequests"
            empty-text="Nessuna lezione acquistata." />
    </x-ui.page-section>
@endsection
