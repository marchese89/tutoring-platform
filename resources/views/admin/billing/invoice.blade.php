@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('invoice.title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        @php
            $documentUrl = route('protected-files.show', ['path' => $invoice->file_path], false);
        @endphp

        <x-ui.pdf-document-card :src="$documentUrl" :title="__('invoice.admin_order_heading', ['number' => $orderId])"
            :viewer-title="__('invoice.admin_order_title', ['number' => $orderId])" :action-url="$documentUrl" :action-label="__('invoice.open_new_tab')"
            action-target="_blank" />
    </x-ui.page-section>
@endsection
