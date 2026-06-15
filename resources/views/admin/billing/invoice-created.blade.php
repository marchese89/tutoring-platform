@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Fattura creata" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <x-ui.feedback-card title="Fattura creata"
                    text="La fattura è stata generata correttamente ed è disponibile nell'archivio."
                    :action-url="route('admin.invoices.index')" action-label="Vai alle fatture" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
