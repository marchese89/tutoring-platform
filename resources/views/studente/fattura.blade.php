@extends('layouts.dashboard-studente')

@section('inner')
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.orders.index') }}">Ordini</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.orders.show', request('id')) }}">Ordine</a>
        </li>
    </ul>
    <div class="container">
        @php
            use App\Models\Invoice;
        @endphp
        <h4>Fattura</h4>
        <iframe width="90%"
            src="/protected-files/{{ Invoice::where('order_id', '=', request('id'))->first()->path }}#view=FitH"
            height="800px">
        </iframe>
    </div>
@endsection
