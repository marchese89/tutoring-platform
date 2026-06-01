@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Chat Studenti'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.table-card title="Elenco Chat">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo Prodotto</th>
                        <th>Titolo</th>
                        <th>Studente</th>
                        <th class="text-center">Stato</th>
                        <th>Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($chat as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->tipo_stringa }}</td>
                            <td>{{ $item->nome_prodotto }}</td>
                            <td>{{ $item->studente_nome }}</td>
                            <td class="text-center">
                                <x-ui.status-dot
                                    :variant="$item->non_letta_admin ? 'danger' : 'success'"
                                    :label="$item->non_letta_admin ? 'Da leggere' : 'Letta'"
                                />
                            </td>
                            <td>
                                <x-ui.primary-button
                                    href="{{ route('admin.chats.show', $item->id) }}"
                                    size="sm"
                                >
                                    Visualizza Chat
                                </x-ui.primary-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Nessuna chat presente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
