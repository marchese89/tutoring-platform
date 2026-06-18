@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.billing.view_invoice')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.pdf-document-card :src="route('protected-files.show', ['path' => $invoice->file_path], false)"
            :title="__('admin.billing.invoice_document')" />
    </x-ui.page-section>
@endsection
