@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.feedback-card title="Registrazione completata" text="La registrazione è andata a buon fine."
                    :action-url="route('login')" action-label="Accedi" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
