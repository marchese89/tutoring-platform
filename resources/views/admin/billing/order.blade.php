@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Ordine #' . $order->id" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.order-details :order-number="$order->id" :order-date="$orderDate" :products="$products" :total="$orderTotal"
            :invoice-url="route('admin.orders.invoice', $order->id)" />
    </x-ui.page-section>
@endsection
