@extends('layouts.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Ordine #' . request('id')" />
@endsection

@section('inner')
    <div class="container py-4">
        <div class="col-12">

            <x-ui.card>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="text-start">
                        <h4 class="fw-bold mb-1">
                            Ordine #{{ request('id') }}
                        </h4>

                        <p class="text-muted mb-0">
                            Data ordine: {{ $ordine->date }}
                        </p>
                    </div>

                    <div class="mt-3 mt-md-0">
                        <button class="btn btn-primary px-4"
                            onclick="location.href='{{ route('admin.orders.invoice', request('id')) }}'">
                            Visualizza Fattura
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle mb-0">

                        <thead class="table-light">
                            <tr>
                                <th>ID Prodotto</th>
                                <th>Tipo Prodotto</th>
                                <th class="text-end">Prezzo</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($prodotti as $item)
                                <tr>

                                    <td class="fw-semibold">
                                        #{{ $item->id_prodotto }}
                                    </td>

                                    <td>
                                        @switch($item->tipo_prodotto)
                                            @case(0)
                                                <span class="badge bg-primary-subtle text-primary">
                                                    Lezione
                                                </span>
                                            @break

                                            @case(2)
                                                <span class="badge bg-success-subtle text-success">
                                                    Esercizio
                                                </span>
                                            @break

                                            @case(5)
                                                <span class="badge bg-warning-subtle text-dark">
                                                    Lezione su richiesta
                                                </span>
                                            @break
                                        @endswitch
                                    </td>

                                    <td class="text-end fw-bold">
                                        {{ number_format($item->price, 2, ',', '.') }} €
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end fw-bold border-0 pt-4">
                                    Totale Ordine
                                </td>

                                <td class="text-end fw-bold border-0 pt-4">
                                    {{ number_format($tot_ordine, 2, ',', '.') }} €
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </x-ui.card>

        </div>
    </div>
@endsection
