@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Nuovo Corso'" />
@endsection

@section('inner')
    <div class="container">

        {{-- FORM --}}
        <div class="row justify-content-center mb-3">
            <div class="col-lg-6">

                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        <h4 class="fw-bold mb-3">Nuovo Corso</h4>

                        <form method="POST" action="{{ route('admin.courses.store') }}">
                            @csrf

                            <label class="form-label">Materia</label>
                            <select class="form-select mb-3" name="subject_id">
                                @foreach ($materie as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->themeArea->name }} - {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>

                            <label class="form-label">Nome corso</label>
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

                <h4 class="fw-bold mb-4">Corsi Inseriti</h4>

                <div class="table-responsive">
                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Area Tematica</th>
                                <th>Materia</th>
                                <th>Nome</th>
                                <th style="width: 30%">Modifica</th>
                                <th>Operazioni</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($corsi as $item)
                                <tr>

                                    <td>{{ $item->id }}</td>

                                    <td>{{ $item->subject->themeArea->name ?? '-' }}</td>

                                    <td>{{ $item->subject->name ?? '-' }}</td>

                                    <td>{{ $item->name }}</td>

                                    {{-- UPDATE --}}
                                    <td>
                                        <form method="POST" action="{{ route('admin.courses.update', $item->id) }}" class="d-flex gap-2">
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
                                        <form method="POST" action="{{ route('admin.courses.destroy', $item->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-outline-danger btn-sm">
                                                Elimina
                                            </button>
                                        </form>
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
