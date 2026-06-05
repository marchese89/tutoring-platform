@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Ordine #' . $order->id" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card class="mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="fw-bold mb-1">
                        Ordine #{{ $order->id }}
                    </h4>

                    <p class="text-muted mb-0">
                        {{ $orderDate }}
                    </p>
                </div>

                <x-ui.primary-button href="{{ route('student.invoices.show', $order->id) }}">
                    Visualizza fattura
                </x-ui.primary-button>
            </div>
        </x-ui.card>

        <x-ui.table-card title="Prodotti acquistati">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tipo prodotto</th>
                        <th class="text-end">Prezzo</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $item)
                        <tr>
                            <td class="fw-semibold">
                                {{ $item['id'] }}
                            </td>

                            <td>
                                {{ ucfirst($item['type']) }}
                            </td>

                            <td class="text-end">
                                {{ number_format($item['price'], 2, ',', '.') }}&euro;
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="2">
                            Totale
                        </th>

                        <th class="text-end">
                            {{ number_format($orderTotal, 2, ',', '.') }}&euro;
                        </th>
                    </tr>
                </tfoot>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
