@extends('layouts.dashboard-admin')

@section('page-title')
    <div class="container">
        <h2 class="fw-bold mb-1" style="font-size: 2.5rem;">
            Elenco Corsi
        </h2>
    </div>
@endsection

@section('inner')
    <div class="container">

        {{-- HEADER --}}
        <div class="mb-4 text-center">
            <h2>Elenco Corsi</h2>
        </div>

        {{-- TABLE --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <div class="table-responsive">
                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Area Tematica</th>
                                <th>Materia</th>
                                <th>Corso</th>
                                <th>Operazioni</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($corsi as $item)
                                <tr>

                                    <td>{{ $item->id }}</td>

                                    <td>{{ $item->matter->theme_area->name ?? '-' }}</td>

                                    <td>{{ $item->matter->name ?? '-' }}</td>

                                    <td>{{ $item->name }}</td>

                                    <td>
                                        <a href="{{ url('modifica-dettagli-corso/' . $item->id) }}"
                                            class="btn btn-primary btn-sm">
                                            Modifica
                                        </a>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
