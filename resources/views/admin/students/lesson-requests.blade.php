@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Richieste Studenti'" />
@endsection

@section('inner')
    @php
        use App\Helpers\DateHelper;
    @endphp

    <style>
        .status-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: inline-block;
        }
    </style>

    <div class="container">

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Elenco Richieste
                </h4>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titolo</th>
                                <th>Data</th>
                                <th>Stato</th>
                                <th>Operazioni</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($lezioni_su_richiesta as $item)
                                <tr>

                                    <td>
                                        {{ $item->id }}
                                    </td>

                                    <td>
                                        {{ $item->title }}
                                    </td>

                                    <td>
                                        {{ DateHelper::format($item->date) }}
                                    </td>

                                    <td>
                                        @if ($item->escaped == 0)
                                            <span class="status-dot bg-danger"></span>
                                        @else
                                            <span class="status-dot bg-success"></span>
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.lesson-requests.show', ['id' => $item->id]) }}"
                                            class="btn btn-primary btn-sm rounded-pill px-3">
                                            Visualizza
                                        </a>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Nessuna richiesta presente.
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
