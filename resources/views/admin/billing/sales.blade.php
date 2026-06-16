@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.billing.sales_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.period-orders-table :has-orders="$hasOrders" :endpoint="route('admin.orders.table')"
            :order-url-template="route('admin.orders.show', ['id' => '__ORDER_ID__'])" :years="$years ?? []" :months="$months ?? []"
            :selected-year="$selectedYear ?? null" :selected-month="$selectedMonth ?? null" :show-student="true"
            :title="__('admin.billing.registered_sales')" :total-label="__('admin.billing.total_sales')"
            :empty-title="__('admin.billing.no_orders')" :empty-text="__('admin.billing.no_sales_text')"
            id="admin-orders" />
    </x-ui.page-section>
@endsection
