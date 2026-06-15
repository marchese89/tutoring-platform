@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Vendite" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.period-orders-table :has-orders="$hasOrders" :endpoint="route('admin.orders.table')"
            :order-url-template="route('admin.orders.show', ['id' => '__ORDER_ID__'])" :years="$years ?? []" :months="$months ?? []"
            :selected-year="$selectedYear ?? null" :selected-month="$selectedMonth ?? null" :show-student="true"
            title="Vendite registrate" total-label="Totale vendite" empty-title="Nessun ordine presente"
            empty-text="Non risultano ancora vendite registrate nel sistema." id="admin-orders" />
    </x-ui.page-section>
@endsection
