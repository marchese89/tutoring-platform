@extends('layouts.dashboard-studente')

@section('page-title')
    <x-ui.section-header :title="'Corso: ' . $corso->name" />
@endsection

@section('inner')
    <div class="container pb-5">
        <x-ui.card>
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

                <div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                        Corso
                    </span>

                    <h3 class="fw-bold mb-0">
                        {{ $corso->name }}
                    </h3>
                </div>

                <div class="text-lg-end">
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                        Materiale acquistato
                    </span>
                </div>

            </div>
        </x-ui.card>

        <div class="mt-4">
            <x-ui.card>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">
                        Lezioni
                    </h4>

                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                        {{ $lezioni->count() }} disponibili
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Numero</th>
                                <th>Titolo</th>
                                <th class="text-end">Operazioni</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($lezioni as $lezione)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ $lezione->id }}
                                    </td>

                                    <td>
                                        {{ $lezione->number }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $lezione->title }}
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('student.lessons.show', ['id_corso' => $corso->id, 'id_lezione' => $lezione->id]) }}"
                                            class="btn btn-primary btn-sm rounded-pill px-3">
                                            Visualizza
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Nessuna lezione acquistata.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        <div class="mt-4">
            <x-ui.card>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">
                        Esercizi
                    </h4>

                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                        {{ $esercizi->count() }} disponibili
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Titolo</th>
                                <th class="text-end">Operazioni</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($esercizi as $esercizio)
                                <tr>
                                    <td class="fw-semibold">
                                        {{ $esercizio->id }}
                                    </td>

                                    <td class="fw-semibold">
                                        {{ $esercizio->title }}
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('student.exercises.show', ['id_corso' => $corso->id, 'id_esercizio' => $esercizio->id]) }}"
                                            class="btn btn-primary btn-sm rounded-pill px-3">
                                            Visualizza
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        Nessun esercizio acquistato.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

    </div>
@endsection
