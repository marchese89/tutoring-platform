@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Ordine #' . $order->id" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.order-details :order-number="$order->id" :order-date="$orderDate" :products="$products" :total="$orderTotal"
            :invoice-url="$order->invoice ? route('student.invoices.show', $order->invoice->id) : null" />
    </x-ui.page-section>
@endsection
