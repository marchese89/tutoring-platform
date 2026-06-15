@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.orders.title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.period-orders-table :has-orders="$hasOrders" :endpoint="route('student.orders.table')"
            :order-url-template="route('student.orders.show', ['id' => '__ORDER_ID__'])" :years="$years ?? []" :months="$months ?? []"
            :selected-year="$selectedYear ?? null" :selected-month="$selectedMonth ?? null"
            :title="__('student.orders.history_title')" :total-label="__('ui.orders.total_orders')"
            :empty-title="__('ui.orders.empty_title')" :empty-text="__('student.orders.empty_text')" id="student-orders" />
    </x-ui.page-section>
@endsection
