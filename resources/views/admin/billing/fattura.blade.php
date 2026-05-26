@extends('layouts.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Fattura'" />
@endsection

@section('inner')
    <div class="container">
        @php
            use App\Models\Invoice;
        @endphp
        <h4>Fattura</h4>
        <iframe width="90%"
            src="/protected_file/{{ Invoice::where('order_id', '=', request('id'))->first()->path }}#view=FitH"
            height="800px">
        </iframe>
    </div>
@endsection
