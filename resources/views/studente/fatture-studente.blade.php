@extends('layouts.dashboard-studente')

@section('page-title')
    <x-ui.section-header title="Lezioni Private" />
@endsection

@section('inner')
    <div class="row g-0 container-fluid" style="text-align: center">
        <h3>Fatture Lezioni Private</h3>
        @php
            use App\Models\StudentInvoice;
            $fatture0 = StudentInvoice::where('student_id', '=', auth()->user()->student->id)->get();
            $cont = StudentInvoice::where('student_id', '=', auth()->user()->student->id)->count();
        @endphp
        @if ($cont > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Operazioni</th>
                    </tr>
                </thead>
                <tbody>
                    @php

                    @endphp
                    @foreach ($fatture0 as $item)
                        <tr>

                            <th scope="row">{{ $item->id }}</th>
                            <td>
                                <button class="btn btn-primary"
                                    onclick="location.href='{{ route('student.invoice-sheets.show', $item->invoice_sheet_id) }}'">Visualizza</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <h3>Non ci sono fatture!</h3>
        @endif
    @endsection
