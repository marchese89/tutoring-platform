@extends('layouts.dashboard-admin')

@section('page-title')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2>Chat Studenti</h2>
            </div>
        </div>
    </div>
@endsection

@section('inner')
    <style>
        .status-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>

    <div class="container">
        {{-- CARD --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Elenco Chat
                </h4>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo Prodotto</th>
                                <th>Titolo</th>
                                <th>Studente</th>
                                <th>Stato</th>
                                <th>Operazioni</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($chat as $item)
                                <tr>

                                    <td>
                                        {{ $item->id }}
                                    </td>

                                    <td>
                                        {{ $item->tipo_stringa }}
                                    </td>

                                    <td>
                                        {{ $item->nome_prodotto }}
                                    </td>

                                    <td>
                                        {{ $item->studente_nome }}
                                    </td>

                                    <td class="text-center">

                                        @if ($item->non_letta_admin)
                                            <span class="status-dot bg-danger"></span>
                                        @else
                                            <span class="status-dot bg-success"></span>
                                        @endif

                                    </td>

                                    <td>
                                        <a href="visualizza-chat-{{ $item->id }}"
                                            class="btn btn-primary btn-sm rounded-pill px-3">
                                            Visualizza Chat
                                        </a>
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

                </div>

            </div>
        </div>

    </div>
@endsection
