@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Fattura" />
@endsection

@section('inner')
    <div class="container">
        <h4>Fattura</h4>
        <iframe width="90%" src="/protected-files/{{ $invoiceSheet->file }}#view=FitH" height="800px">
        </iframe>
    </div>
@endsection
