@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Richieste Dirette'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.table-card title="Richieste non acquistate">
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
                    @forelse ($directRequests as $item)
                        <tr>
                            <th scope="row">{{ $item->id }}</th>
                            <td>
                                {{ $item->title }}
                            </td>
                            <td>
                                {{ $item->date }}
                            </td>
                            <td class="text-center">
                                <x-ui.status-dot
                                    :variant="$item->escaped == 0 ? 'danger' : 'success'"
                                    :label="$item->escaped == 0 ? 'Da svolgere' : 'Svolta'"
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
                                Nessuna richiesta diretta presente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
