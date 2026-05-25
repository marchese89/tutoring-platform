@extends('layouts.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Materie'" />
@endsection


@section('inner')
    <div class="container">

        {{-- FORM --}}
        <div class="row justify-content-center mb-5">
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-3">Nuova Materia</h4>

                        <form method="POST" action="/matter">
                            @csrf

                            <label class="form-label">Area Tematica</label>
                            <select class="form-select mb-3" name="theme_area_id">
                                @foreach ($aree_t as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>

                            <label class="form-label">Nome</label>
                            <input type="text" class="form-control mb-3" name="name" maxlength="255">

                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                Inserisci
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>

        {{-- TABLE --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">Materie Inserite</h4>

                <div class="table-responsive">
                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Area Tematica</th>
                                <th>Nome</th>
                                <th style="width: 35%">Modifica</th>
                                <th>Operazioni</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($materie as $item)
                                <tr>

                                    <td>{{ $item->id }}</td>

                                    <td>{{ $item->theme_area->name ?? '-' }}</td>

                                    <td>{{ $item->name }}</td>

                                    {{-- UPDATE --}}
                                    <td>
                                        <form method="POST" action="/matter/{{ $item->id }}" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')

                                            <input type="text" class="form-control" name="name"
                                                value="{{ $item->name }}" maxlength="255">

                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Modifica
                                            </button>

                                        </form>
                                    </td>

                                    {{-- DELETE --}}
                                    <td>
                                        @if ($item->courses->count() == 0)
                                            <form method="POST" action="/matter/{{ $item->id }}">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    Elimina
                                                </button>
                                            </form>
                                        @endif
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
