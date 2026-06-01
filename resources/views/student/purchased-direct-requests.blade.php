@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Lezioni su Richiesta'" />
@endsection

@section('inner')
    @php
        use App\Helpers\DateHelper;
        use App\Models\LessonOnRequest;
        use App\Models\ChatMessage;
        use App\Models\Chat;
    @endphp
    @php
        $lezioni_su_richiesta = LessonOnRequest::where('student_id', '=', auth()->user()->student->id)
            ->where('paid', '=', 1)
            ->orderBy('date', 'desc')
            ->get();
    @endphp

    <x-ui.page-section>
        <x-ui.table-card title="Lezioni acquistate">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titolo</th>
                        <th scope="col">Data</th>
                        <th scope="col" class="text-center">Stato</th>
                        <th scope="col">Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($lezioni_su_richiesta as $item)
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>
                                {{ $item->title }}
                            </td>
                            <td>
                                {{ DateHelper::format($item->date) }}
                            </td>
                            <td class="text-center">
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
                                            $hasUnreadMessage = true;
                                        } else {
                                            $hasUnreadMessage = false;
                                        }
                                    } else {
                                        $hasUnreadMessage = false;
                                    }
                                @endphp

                                <x-ui.status-dot
                                    :variant="$hasUnreadMessage ? 'danger' : 'success'"
                                    :label="$hasUnreadMessage ? 'Da leggere' : 'Nessun nuovo messaggio'"
                                />
                            </td>
                            <td>
                                <x-ui.primary-button
                                    href="{{ route('student.direct-requests.show', ['id' => $item->id]) }}"
                                    size="sm"
                                >
                                    Visualizza
                                </x-ui.primary-button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Nessuna lezione acquistata.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
