@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.billing.invoice_created_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <x-ui.feedback-card :title="__('admin.billing.invoice_created_title')"
                    :text="__('admin.billing.invoice_created_text')" :action-url="route('admin.invoices.index')"
                    :action-label="__('admin.billing.go_to_invoices')" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
