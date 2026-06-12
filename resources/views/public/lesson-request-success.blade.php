@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.feedback-card title="Richiesta inviata" text="La richiesta di supporto è stata inviata correttamente."
                    :action-url="route('student.dashboard')" action-label="Torna alla dashboard" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
