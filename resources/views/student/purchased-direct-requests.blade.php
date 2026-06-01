@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Lezioni su Richiesta'" />
@endsection

@section('inner')
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
                    @forelse ($purchasedDirectRequests as $item)
                        <tr>
                            <th scope="row">{{ $item['id'] }}</th>
                            <td>
                                {{ $item['title'] }}
                            </td>
                            <td>
                                {{ $item['date'] }}
                            </td>
                            <td class="text-center">
                                <x-ui.status-dot
                                    :variant="$item['has_unread_message'] ? 'danger' : 'success'"
                                    :label="$item['has_unread_message'] ? 'Da leggere' : 'Nessun nuovo messaggio'"
                                />
                            </td>
                            <td>
                                <x-ui.primary-button
                                    href="{{ route('student.direct-requests.show', ['id' => $item['id']]) }}"
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
