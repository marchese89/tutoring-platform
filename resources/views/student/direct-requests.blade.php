@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Richieste Dirette'" />
@endsection

@section('inner')
    <style>
        .cerchio {
            width: 40px;
            height: 40px;
            background-color: red;
            border-radius: 50%;
        }
    </style>
    @php
        use App\Models\LessonOnRequest;
    @endphp
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Titolo</th>
                    <th scope="col">Data</th>
                    <th scope="col">Svolta</th>
                    <th scope="col">Operazioni</th>
                </tr>
            </thead>
            @php
                $lezioni_su_richiesta = LessonOnRequest::where('student_id', '=', auth()->user()->student->id)
                    ->where('paid', '=', 0)
                    ->get();
            @endphp
            <tbody>
                @foreach ($lezioni_su_richiesta as $item)
                    <tr>

                        <th scope="row">{{ $item->id }}</th>
                        <td>
                            {{ $item->title }}
                        </td>
                        <td>
                            {{ $item->date }}
                        </td>
                        <td>
                            @php
                                $r = $item->escaped;
                                if ($r == 0) {
                                    echo '<div class="cerchio" style="background-color: red;"></div>';
                                } else {
                                    echo '<div class="cerchio" style="background-color: green;"></div>';
                                }
                            @endphp
                        </td>
                        <td>
                            <div>
                                <button type="submit" class="btn btn-primary"
                                    onclick="location.href='{{ route('student.direct-requests.show', ['id' => $item->id]) }}'">Visualizza</button>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
