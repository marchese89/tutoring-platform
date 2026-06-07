@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Ordine #' . $order->id" />
@endsection

@section('inner')
    <div class="container py-4">
        <div class="col-12">

            <x-ui.card>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div class="text-start">
                        <h4 class="fw-bold mb-1">
                            Ordine #{{ $order->id }}
                        </h4>

                        <p class="text-muted mb-0">
                            Data ordine: {{ $orderDate }}
                        </p>
                    </div>

                    <div class="mt-3 mt-md-0">
                        <button class="btn btn-primary px-4"
                            onclick="location.href='{{ route('admin.orders.invoice', $order->id) }}'">
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
                            @foreach ($products as $item)
                                <tr>

                                    <td class="fw-semibold">
                                        #{{ $item['product_id'] }}
                                    </td>

                                    <td>
                                        <span class="badge {{ $item['product_type_class'] }}">
                                            {{ $item['product_type_label'] }}
                                        </span>
                                    </td>

                                    <td class="text-end fw-bold">
                                        {{ number_format($item['price'], 2, ',', '.') }} €
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
                                    {{ number_format($orderTotal, 2, ',', '.') }} €
                                </td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </x-ui.card>

        </div>
    </div>
@endsection
