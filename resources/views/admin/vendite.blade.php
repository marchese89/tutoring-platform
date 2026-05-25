@extends('layouts.dashboard-admin')

@section('page-title')
    <div class="container my-4">
        <h1 class="fw-bold mb-1" style="font-size: 2.5rem;">
            Vendite
        </h1>

        <p class="text-muted mb-0">
            Monitoraggio ordini e riepilogo vendite mensili.
        </p>
    </div>
@endsection

@section('inner')

    <script>
        function aggiorna_tabella(anno, mese) {

            fetch(`/cambia_tabella_ordini?anno=${anno}&mese=${mese}`)
                .then(res => res.json())
                .then(data => {

                    let html = '';

                    data.ordini.forEach(o => {

                        html += `
                            <tr>
                                <td class="fw-semibold">${o.id}</td>

                                <td>${o.studente}</td>

                                <td>${o.data}</td>

                                <td class="fw-semibold text-success">
                                    ${o.totale}€
                                </td>

                                <td>
                                    <button class="btn btn-primary btn-sm rounded-pill px-3"
                                        onclick="location.href='admin-ordine-${o.id}'">

                                        Visualizza

                                    </button>
                                </td>
                            </tr>
                        `;
                    });

                    document.getElementById('tabella-body').innerHTML = html;

                    document.getElementById('totale').innerHTML =
                        `
                            <div class="fw-bold fs-5">
                                Totale Vendite: ${data.totale}€
                            </div>
                        `;
                });
        }

        window.onload = function() {

            aggiorna_tabella(
                document.getElementById('floatingSelect1').value,
                document.getElementById('floatingSelect2').value
            );

        }
    </script>

    @php
        use App\Models\Order;
        use App\Models\OrderProduct;
        use App\Services\AcquistiService;
        use App\Helpers\DateHelper;
        use App\Models\User;
        use App\Models\Student;
        use Illuminate\Support\Facades\DB;

        $primo_ordine = DB::table('orders')->orderBy(DB::raw('date'), 'desc')->first();

        if ($primo_ordine != null) {
            $data_primo = DateHelper::parse($primo_ordine->date);

            $ordine = DB::table('orders')
                ->whereMonth('date', $data_primo['mese'])
                ->whereYear('date', $data_primo['anno'])
                ->orderBy(DB::raw('date'), 'desc')
                ->get();

            $years = DB::table('orders')
                ->select(DB::raw('YEAR(date) as year'))
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->get();

            $months = DB::table('orders')
                ->select(DB::raw('MONTH(date) as month'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
        }
    @endphp
    <div class="container">
        <div class="container-fluid">

            @if ($primo_ordine != null)
                {{-- FILTRI --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4">

                    <div class="card-body p-4">

                        <div class="row g-3 align-items-end">

                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Anno
                                </label>

                                <select class="form-select rounded-3" id="floatingSelect1"
                                    onchange="aggiorna_tabella(_('floatingSelect1').value,_('floatingSelect2').value)">

                                    <option selected value="{{ $data_primo['anno'] }}">
                                        {{ $data_primo['anno'] }}
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
                                    onchange="aggiorna_tabella(_('floatingSelect1').value,_('floatingSelect2').value)">

                                    <option selected value="{{ $data_primo['mese'] }}">
                                        {{ AcquistiService::stringa_mese(intval($data_primo['mese'])) }}
                                    </option>

                                    @foreach ($months as $item)
                                        <option value="{{ $item->month }}">
                                            {{ AcquistiService::stringa_mese(intval($item->month)) }}
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
                                        <td colspan="5" class="text-end pt-4" id="totale">
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
