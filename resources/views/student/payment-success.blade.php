@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Pagamento completato'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.feedback-card title="Fattura creata" text="Il pagamento è stato completato correttamente."
                    :action-url="route('student.dashboard')" action-label="Torna alla dashboard" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
