@extends('layouts.student-dashboard')

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
        <iframe width="90%" src="/protected-files/{{ $fattura->file }}#view=FitH" height="800px">
        </iframe>
    </div>
@endsection
