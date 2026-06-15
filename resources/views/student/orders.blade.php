@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Ordini" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.period-orders-table :has-orders="$hasOrders" :endpoint="route('student.orders.table')"
            :order-url-template="route('student.orders.show', ['id' => '__ORDER_ID__'])" :years="$years ?? []" :months="$months ?? []"
            :selected-year="$selectedYear ?? null" :selected-month="$selectedMonth ?? null" title="Ordini effettuati"
            total-label="Totale ordini" empty-title="Non ci sono ordini"
            empty-text="Non risultano ancora ordini associati al tuo account." id="student-orders" />
    </x-ui.page-section>
@endsection
