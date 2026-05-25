@extends('layouts.dashboard-admin')

@section('page-title')
    <div class="container my-4">
        <h2>Elenco Corsi</h2>
    </div>
@endsection

@section('inner')
    <div class="container" style="text-align:center; width:80%">

        <table class="table">
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
                @foreach ($courses as $course)
                    <tr>
                        <th scope="row">{{ $course->id }}</th>

                        <td>{{ $course->matter->theme_area->name }}</td>

                        <td>{{ $course->matter->name }}</td>

                        <td>{{ $course->name }}</td>

                        <td>
                            <button class="btn btn-primary" onclick="location.href='/course/{{ $course->id }}'">
                                Visualizza
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
@endsection
