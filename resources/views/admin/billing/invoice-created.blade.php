@extends('layouts.admin-dashboard')

@section('inner')
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </li>
    </ul>

    <div class="container" style="width: 80%;text-align:center">
        <br>
        <br>
        <h3>Fattura Creata!</h3>
    </div>
@endsection
