@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Richieste dirette" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-table title="Richieste non acquistate" :requests="$directRequests"
            empty-text="Nessuna richiesta diretta presente." />
    </x-ui.page-section>
@endsection
