@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.feedback-card title="Acquisto completato" text="Il pagamento è stato completato correttamente."
                    :action-url="route('student.orders.index')" action-label="Visualizza gli ordini" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
