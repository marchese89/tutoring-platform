@extends('layouts.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Elenco Fatture'" />
@endsection

@section('inner')

    <div class="container">

        @php
            use App\Models\Invoice;
            use App\Helpers\DateHelper;
        @endphp

        @if ($fatture->count() > 0)
            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">
                        Fatture
                    </h4>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Numero</th>
                                    <th>Data</th>
                                    <th>Operazioni</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($fatture as $item)
                                    <tr>

                                        <td>
                                            {{ $item->number }}
                                        </td>

                                        <td>
                                            {{ DateHelper::format($item->date) }}
                                        </td>

                                        <td>
                                            <a href="{{ route('visualizza-fattura', $item->number) }}"
                                                class="btn btn-primary btn-sm rounded-pill px-3">
                                                Visualizza
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>
        @else
            <div class="text-center py-5">
                <h4 class="text-muted">Non ci sono fatture</h4>
            </div>
        @endif

    </div>

@endsection
