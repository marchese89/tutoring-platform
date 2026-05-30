@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Elenco Corsi'" />
@endsection

@section('inner')
    <div class="container" style="text-align:center;">

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
                            <button class="btn btn-primary"
                                onclick="location.href='{{ route('student.courses.show', $course->id) }}'">
                                Visualizza
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

    </div>
@endsection
