@props([
    'orderNumber',
    'orderDate',
    'products',
    'total',
    'invoiceUrl' => null,
])

<div data-order-details>
    <x-ui.card class="mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h4 class="fw-bold mb-1">
                    Ordine #{{ $orderNumber }}
                </h4>

                <p class="text-muted mb-0">
                    Data ordine: {{ $orderDate }}
                </p>
            </div>

            @if ($invoiceUrl)
                <x-ui.primary-button href="{{ $invoiceUrl }}">
                    Visualizza fattura
                </x-ui.primary-button>
            @endif
        </div>
    </x-ui.card>

    <x-ui.table-card title="Prodotti acquistati">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">ID prodotto</th>
                    <th scope="col">Tipo prodotto</th>
                    <th scope="col" class="text-end">Prezzo</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($products as $item)
                    <tr>
                        <td class="fw-semibold">
                            #{{ $item['id'] }}
                        </td>

                        <td>
                            <span class="badge {{ $item['type_class'] }}">
                                {{ $item['type_label'] }}
                            </span>
                        </td>

                        <td class="text-end fw-semibold">
                            {{ number_format($item['price'], 2, ',', '.') }}&euro;
                        </td>
                    </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th colspan="2" class="text-end border-0 pt-4">
                        Totale ordine
                    </th>

                    <th class="text-end border-0 pt-4">
                        {{ number_format($total, 2, ',', '.') }}&euro;
                    </th>
                </tr>
            </tfoot>
        </table>
    </x-ui.table-card>
</div>
