@props([
    'hasOrders',
    'endpoint',
    'orderUrlTemplate',
    'years' => [],
    'months' => [],
    'selectedYear' => null,
    'selectedMonth' => null,
    'showStudent' => false,
    'title' => null,
    'totalLabel' => null,
    'emptyTitle' => null,
    'emptyText' => null,
    'id' => 'period-orders',
])

@php
    $title ??= __('ui.orders.title');
    $totalLabel ??= __('ui.orders.total_orders');
    $emptyTitle ??= __('ui.orders.empty_title');
@endphp

@if ($hasOrders)
    <div id="{{ $id }}" data-period-orders-table data-endpoint="{{ $endpoint }}"
        data-order-url-template="{{ $orderUrlTemplate }}" data-show-student="{{ $showStudent ? 'true' : 'false' }}"
        data-total-label="{{ $totalLabel }}">
        <x-ui.card class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold" for="{{ $id }}-year">
                        {{ __('ui.orders.year') }}
                    </label>

                    <select class="form-select rounded-3" id="{{ $id }}-year" data-orders-year>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" @selected($year == $selectedYear)>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold" for="{{ $id }}-month">
                        {{ __('ui.orders.month') }}
                    </label>

                    <select class="form-select rounded-3" id="{{ $id }}-month" data-orders-month>
                        @foreach ($months as $month)
                            <option value="{{ $month['value'] }}" @selected($month['value'] == $selectedMonth)>
                                {{ $month['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </x-ui.card>

        <x-ui.table-card :title="$title">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">#</th>

                        @if ($showStudent)
                            <th scope="col">{{ __('ui.orders.student') }}</th>
                        @endif

                        <th scope="col">{{ __('ui.table.date') }}</th>
                        <th scope="col">{{ __('ui.orders.total') }}</th>
                        <th scope="col">{{ __('ui.table.actions') }}</th>
                    </tr>
                </thead>

                <tbody data-orders-body>
                    <tr>
                        <td colspan="{{ $showStudent ? 5 : 4 }}" class="text-center text-muted py-4">
                            {{ __('ui.orders.loading') }}
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="{{ $showStudent ? 5 : 4 }}" class="text-end pt-4 fw-bold" data-orders-total>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </x-ui.table-card>
    </div>
@else
    <x-ui.empty-state :title="$emptyTitle" :text="$emptyText" />
@endif

@once
    @push('scripts')
        <script>
            document.querySelectorAll('[data-period-orders-table]').forEach((table) => {
                const yearSelect = table.querySelector('[data-orders-year]');
                const monthSelect = table.querySelector('[data-orders-month]');
                const tableBody = table.querySelector('[data-orders-body]');
                const totalCell = table.querySelector('[data-orders-total]');
                const columnCount = table.dataset.showStudent === 'true' ? 5 : 4;
                const currencyFormatter = new Intl.NumberFormat(document.documentElement.lang, {
                    style: 'currency',
                    currency: 'EUR',
                });

                const appendCell = (row, value, className = '') => {
                    const cell = document.createElement('td');
                    cell.textContent = value;
                    cell.className = className;
                    row.appendChild(cell);
                };

                const showMessage = (message) => {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.colSpan = columnCount;
                    cell.className = 'text-center text-muted py-4';
                    cell.textContent = message;
                    row.appendChild(cell);
                    tableBody.replaceChildren(row);
                    totalCell.textContent = '';
                };

                const loadOrders = async () => {
                    showMessage(@json(__('ui.orders.loading')));

                    try {
                        const url = new URL(table.dataset.endpoint, window.location.origin);
                        url.searchParams.set('year', yearSelect.value);
                        url.searchParams.set('month', monthSelect.value);

                        const response = await fetch(url, {
                            headers: {
                                Accept: 'application/json',
                            },
                        });

                        if (!response.ok) {
                            throw new Error('Order request failed');
                        }

                        const data = await response.json();

                        if (!data.orders.length) {
                            showMessage(@json(__('ui.orders.period_empty')));
                            return;
                        }

                        const rows = data.orders.map((order) => {
                            const row = document.createElement('tr');
                            appendCell(row, order.id, 'fw-semibold');

                            if (table.dataset.showStudent === 'true') {
                                appendCell(row, order.student);
                            }

                            appendCell(row, order.date);
                            appendCell(row, currencyFormatter.format(Number(order.total)),
                                'fw-semibold text-success');

                            const actionCell = document.createElement('td');
                            const link = document.createElement('a');
                            link.href = table.dataset.orderUrlTemplate.replace('__ORDER_ID__', encodeURIComponent(order.id));
                            link.className = 'btn btn-primary btn-sm rounded-pill px-3 py-1';
                            link.textContent = @json(__('ui.table.view'));
                            actionCell.appendChild(link);
                            row.appendChild(actionCell);

                            return row;
                        });

                        tableBody.replaceChildren(...rows);
                        totalCell.textContent = `${table.dataset.totalLabel}: ${currencyFormatter.format(Number(data.total))}`;
                    } catch (error) {
                        showMessage(@json(__('ui.orders.load_error')));
                    }
                };

                yearSelect.addEventListener('change', loadOrders);
                monthSelect.addEventListener('change', loadOrders);
                loadOrders();
            });
        </script>
    @endpush
@endonce
