@extends('layouts.student-dashboard')

@section('inner')
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.orders.index') }}">Ordini</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.orders.show', $id) }}">Ordine</a>
        </li>
    </ul>
    <div class="container">
        <h4>Fattura</h4>
        <iframe width="90%" src="/protected-files/{{ $invoice->path }}#view=FitH" height="800px">
        </iframe>
    </div>
@endsection
