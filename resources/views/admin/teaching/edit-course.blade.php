@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Corso'" />
@endsection

@section('inner')
    <div class="container">

        {{-- HEADER --}}
        <div class="mb-4 text-center">
            <h2>Modifica Corso</h2>
            <h3 class="text-muted">{{ $corso->name }}</h3>
        </div>

        {{-- LEZIONI --}}
        <div class="mb-5">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Lezioni</h3>

                <a href="{{ route('admin.lessons.create', $corso->id) }}" class="btn btn-primary">
                    Nuova Lezione
                </a>
            </div>

            <table class="table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titolo</th>
                        <th>Prezzo</th>
                        <th>Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($lezioni as $item)
                        <tr>

                            <td>{{ $item->number }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->price }} €</td>

                            <td>
                                <a class="btn btn-primary btn-sm"
                                    href="{{ route('admin.lessons.edit', ['course' => $corso->id, 'lesson' => $item->id]) }}">
                                    Modifica
                                </a>

                                <form method="POST" action="{{ route('admin.lessons.destroy', $item->id) }}" style="display:inline">
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

        {{-- ESERCIZI --}}
        <div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>Esercizi</h3>

                <a href="{{ route('admin.exercises.create', $corso->id) }}" class="btn btn-primary">
                    Nuovo Esercizio
                </a>
            </div>

            <table class="table">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titolo</th>
                        <th>Prezzo</th>
                        <th>Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($esercizi as $item)
                        <tr>

                            <td>{{ $item->id }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->price }} €</td>

                            <td>
                                <a class="btn btn-primary btn-sm"
                                    href="{{ route('admin.exercises.edit', ['course' => $corso->id, 'exercise' => $item->id]) }}">
                                    Modifica
                                </a>

                                <form method="POST" action="{{ route('admin.exercises.destroy', $item->id) }}" style="display:inline">
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
@endsection
