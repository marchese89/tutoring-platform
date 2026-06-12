@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.feedback-card title="Registrazione non completata"
                    text="L'utente risulta già registrato. Verifica i dati e riprova." icon="bi-exclamation-circle-fill"
                    icon-class="text-danger" :action-url="route('register')" action-label="Riprova" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
