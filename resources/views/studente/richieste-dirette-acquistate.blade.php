@extends('layouts.dashboard-studente')

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
        use App\Helpers\DateHelper;
        use App\Models\LessonOnRequest;
        use App\Models\ChatMessage;
        use App\Models\Chat;
    @endphp
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.dashboard') }}">Dashboard</a>
        </li>
    </ul>
    <div class="row g-0 container-fluid">
        <div>
            <h3 style="text-align: center">Lezioni su Richiesta Acquistate</h3>
        </div>
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
                    ->where('paid', '=', 1)
                    ->orderBy('date', 'desc')
                    ->get();
            @endphp
            <tbody>

                @if (!$lezioni_su_richiesta->isEmpty())
                    @foreach ($lezioni_su_richiesta as $item)
                        <tr>

                            <th scope="row">{{ $item->id }}</th>
                            <td>
                                {{ $item->title }}
                            </td>
                            <td>
                                {{ DateHelper::format($item->date) }}
                            </td>
                            <td>
                                @php
                                    $chat = Chat::where('id_prodotto', '=', $item->id)
                                        ->where('tipo_prodotto', '=', 5)
                                        ->where('id_studente', '=', auth()->user()->student->id)
                                        ->first();
                                    if ($chat) {
                                        $chat_ = ChatMessage::where('chat_id', '=', $chat->id)
                                            ->orderBy('date', 'desc')
                                            ->first();
                                        if ($chat_ != null && $chat_->author == 1) {
                                            echo '<div class="cerchio" style="background-color: red;"></div>';
                                        } else {
                                            echo '<div class="cerchio" style="background-color: green;"></div>';
                                        }
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
                @endif
            </tbody>
        </table>
    </div>
@endsection
