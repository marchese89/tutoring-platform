@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Vendite'" />
@endsection

@section('inner')

    <script>
        async function aggiorna_tabella(anno, mese) {
            const response = await fetch(`{{ route('admin.orders.table') }}?anno=${anno}&mese=${mese}`);
            const data = await response.json();

            document.getElementById('tabella-body').innerHTML = data.html;
            document.getElementById('totale').textContent = `Totale Vendite: ${data.totale}€`;
        }

        window.onload = function() {
            aggiorna_tabella(
                document.getElementById('floatingSelect1').value,
                document.getElementById('floatingSelect2').value
            );
        }
    </script>

    <div class="container">

        <div class="container-fluid">

            @if ($hasOrders)
                {{-- FILTRI --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body p-4">

                        <div class="row g-3 align-items-end">

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Anno
                                </label>

                                <select class="form-select rounded-3" id="floatingSelect1"
                                    onchange="aggiorna_tabella(
                                        document.getElementById('floatingSelect1').value,
                                        document.getElementById('floatingSelect2').value
                                    )">

                                    <option selected value="{{ $dataPrimo['anno'] }}">

                                        {{ $dataPrimo['anno'] }}

                                    </option>

                                    @foreach ($years as $item)
                                        <option value="{{ $item->year }}">
                                            {{ $item->year }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Mese
                                </label>

                                <select class="form-select rounded-3" id="floatingSelect2"
                                    onchange="aggiorna_tabella(
                                        document.getElementById('floatingSelect1').value,
                                        document.getElementById('floatingSelect2').value
                                    )">

                                    <option selected value="{{ $dataPrimo['mese'] }}">

                                        {{ App\Services\PurchaseService::stringa_mese(intval($dataPrimo['mese'])) }}

                                    </option>

                                    @foreach ($months as $item)
                                        <option value="{{ $item->month }}">

                                            {{ App\Services\PurchaseService::stringa_mese(intval($item->month)) }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- TABELLA --}}
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-4">

                        <div class="table-responsive">

                            <table class="table align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th>#</th>

                                        <th>Studente</th>

                                        <th>Data</th>

                                        <th>Totale</th>

                                        <th>Dettagli</th>

                                    </tr>

                                </thead>

                                <tbody id="tabella-body">
                                </tbody>

                                <tfoot>

                                    <tr>

                                        <td colspan="5" class="text-end pt-4 fw-bold" id="totale">
                                        </td>

                                    </tr>

                                </tfoot>

                            </table>

                        </div>

                    </div>

                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body text-center py-5">

                        <i class="fa-solid fa-box-open fa-3x text-muted mb-4"></i>

                        <h3 class="fw-bold mb-2">
                            Nessun ordine presente
                        </h3>

                        <p class="text-muted mb-0">
                            Non risultano ancora vendite registrate nel sistema.
                        </p>

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection
