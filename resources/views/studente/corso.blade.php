@extends('layouts.dashboard-studente')
@php
    use App\Models\Course;
    use App\Models\Lesson;
    use App\Models\Exercise;
    use App\Services\AcquistiService;

    $corso = Course::where('id', '=', request('id'))->first();

@endphp
@section('page-title')
    <x-ui.section-header :title="'Visualizza Corso'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center;width:35%">
        <h3 class="font-weight-bold">{{ $corso->name }}</h3>
    </div>
    <br>
    <div class="container" style="text-align: center;width:80%">
        <h3>Lezioni</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Numero</th>
                    <th scope="col">Titolo</th>
                    <th scope="col">Operazioni</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $lezioni = Lesson::all();
                @endphp
                @foreach ($lezioni as $item)
                    @if (AcquistiService::prodotto_acquistato(request()->user()->student->id, $item->id, 0))
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>
                                {{ $item->number }}
                            </td>
                            <td>
                                {{ $item->title }}
                            </td>
                            <td>
                                <button class="btn btn-primary"
                                    onclick=location.href="/lezione/{{ request('id') }}/{{ $item->id }}">Visualizza</button>
                            </td>

                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
        <h3>Esercizi</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col"></th>
                    <th scope="col">Titolo</th>
                    <th scope="col">Operazioni</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $esercizi = Exercise::all();
                @endphp
                @foreach ($esercizi as $item)
                    @if (AcquistiService::prodotto_acquistato(request()->user()->student->id, $item->id, 2))
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>

                            </td>
                            <td>
                                {{ $item->title }}
                            </td>
                            <td>
                                <button class="btn btn-primary"
                                    onclick=location.href="/esercizio/{{ request('id') }}/{{ $item->id }}">Visualizza</button>
                            </td>

                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
