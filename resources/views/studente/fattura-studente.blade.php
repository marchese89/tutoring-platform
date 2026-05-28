@extends('layouts.dashboard-studente')

@section('page-title')
    <x-ui.section-header title="Fattura" />
@endsection

@section('inner')
    <div class="container">
        @php
            use App\Models\InvoiceSheet;
            $fattura = InvoiceSheet::where('id', '=', request('id'))->first();
        @endphp
        <h4>Fattura</h4>
        <iframe width="90%" src="/protected_file/{{ $fattura->file }}#view=FitH" height="800px">
        </iframe>
    </div>
@endsection
