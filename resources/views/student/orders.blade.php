@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Ordini'" />
@endsection

@section('inner')
    <script>
        async function aggiorna_tabella(anno, mese) {
            const response = await fetch(`{{ route('student.orders.table') }}?anno=${anno}&mese=${mese}`);
            const data = await response.json();
            const orderShowUrl = "{{ route('student.orders.show', ['id' => '__ORDER_ID__']) }}";
            const tableBody = document.getElementById('tabella-body');
            const totalCell = document.getElementById('totale-ordini');

            if (!data.ordini.length) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Nessun ordine per il periodo selezionato.
                        </td>
                    </tr>
                `;
                totalCell.innerHTML = '';
                return;
            }

            tableBody.innerHTML = data.ordini.map((ordine) => {
                const url = orderShowUrl.replace('__ORDER_ID__', ordine.id);

                return `
                    <tr>
                        <td class="fw-semibold">${ordine.id}</td>
                        <td>${ordine.data}</td>
                        <td class="fw-semibold text-success">${ordine.totale}&euro;</td>
                        <td>
                            <a href="${url}" class="btn btn-primary btn-sm rounded-pill px-3 py-1">
                                Visualizza
                            </a>
                        </td>
                    </tr>
                `;
            }).join('');

            totalCell.innerHTML = `Totale ordini: ${data.totale}&euro;`;
        }

        window.onload = function() {
            const yearSelect = document.getElementById('orders-year');
            const monthSelect = document.getElementById('orders-month');

            if (!yearSelect || !monthSelect) {
                return;
            }

            aggiorna_tabella(yearSelect.value, monthSelect.value);
        }
    </script>

    <x-ui.page-section>
        @if ($hasOrders)
            <x-ui.card class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="orders-year">
                            Anno
                        </label>

                        <select
                            class="form-select rounded-3"
                            id="orders-year"
                            onchange="aggiorna_tabella(
                                document.getElementById('orders-year').value,
                                document.getElementById('orders-month').value
                            )"
                        >
                            @foreach ($years as $year)
                                <option value="{{ $year }}" @selected($year == $selectedYear)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold" for="orders-month">
                            Mese
                        </label>

                        <select
                            class="form-select rounded-3"
                            id="orders-month"
                            onchange="aggiorna_tabella(
                                document.getElementById('orders-year').value,
                                document.getElementById('orders-month').value
                            )"
                        >
                            @foreach ($months as $month)
                                <option value="{{ $month['value'] }}" @selected($month['value'] == $selectedMonth)>
                                    {{ $month['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </x-ui.card>

            <x-ui.table-card title="Ordini effettuati">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Data</th>
                            <th>Totale</th>
                            <th>Operazioni</th>
                        </tr>
                    </thead>

                    <tbody id="tabella-body">
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end pt-4 fw-bold" id="totale-ordini">
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </x-ui.table-card>
        @else
            <x-ui.empty-state
                title="Non ci sono ordini"
                text="Non risultano ancora ordini associati al tuo account."
            />
        @endif
    </x-ui.page-section>
@endsection
