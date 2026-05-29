@extends('layouts.dashboard-studente')

@section('inner')
    <script>
        function aggiorna_tabella(anno, mese) {

            fetch(`{{ route('student.orders.table') }}?anno=${anno}&mese=${mese}`)
                .then(res => res.json())
                .then(data => {

                    let html = '';

                    data.ordini.forEach(o => {
                        html += `
                    <tr>
                        <td>${o.id}</td>
                        <td>${o.data}</td>
                        <td>
                            <button class="btn btn-primary"
                                onclick="location.href='{{ route('student.orders.show', ['id' => '__ORDER_ID__']) }}'.replace('__ORDER_ID__', o.id)">
                                Visualizza
                            </button>
                        </td>
                    </tr>
                `;
                    });

                    document.getElementById('tabella-body').innerHTML = html;
                });
        }
        window.onload = function() {
            aggiorna_tabella(document.getElementById('floatingSelect1').value, document.getElementById(
                'floatingSelect2').value);
        }
    </script>
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.dashboard') }}">Dashboard</a>
        </li>
    </ul>
    <div class="row g-0 container-fluid" style="text-align: center">
        <h3>Ordini Effettuati</h3>

        @php
            use App\Helpers\DateHelper;
            use App\Models\Order;
            use App\Services\AcquistiService;

            $primo_ordine = Order::where('student_id', '=', auth()->user()->student->id)
                ->orderBy('date', 'desc')
                ->first();
            if ($primo_ordine != null) {
                $data_primo = DateHelper::parse($primo_ordine->date);

                $ordini = DB::table('orders')
                    ->where('student_id', '=', auth()->user()->student->id)
                    ->whereMonth('date', $data_primo['mese'])
                    ->whereYear('date', $data_primo['anno'])
                    ->orderBy(DB::raw('date'), 'desc')
                    ->get();

                $years = DB::table('orders')
                    ->select(DB::raw('YEAR(date) as year'))
                    ->where('student_id', '=', auth()->user()->student->id)
                    ->groupBy('year')
                    ->orderBy('year', 'asc')
                    ->get();

                $months = DB::table('orders')
                    ->select(DB::raw('MONTH(date) as month'))
                    ->where('student_id', '=', auth()->user()->student->id)
                    ->groupBy('month')
                    ->orderBy('month', 'asc')
                    ->get();
            }
        @endphp
        @if ($primo_ordine != null)
            <div class="form-floating" style="display: inline">
                <select class="form-select" id="floatingSelect1" aria-label="Floating label select example"
                    onchange="aggiorna_tabella(_('floatingSelect1').value,_('floatingSelect2').value)">
                    <option selected value="{{ $data_primo['anno'] }}">{{ $data_primo['anno'] }}</option>
                    @foreach ($years as $item)
                        <option value="{{ $item->year }}">{{ $item->year }}</option>
                    @endforeach
                </select>
                <label for="floatingSelect">Anno</label>
            </div>
            <div class="form-floating" style="display: inline">
                <select class="form-select" id="floatingSelect2" aria-label="Floating label select example"
                    onchange="aggiorna_tabella(_('floatingSelect1').value,_('floatingSelect2').value)">
                    <option selected value="{{ $data_primo['mese'] }}">
                        {{ AcquistiService::stringa_mese(intval($data_primo['mese'])) }}
                    </option>
                    @foreach ($months as $item)
                        <option value="{{ $item->month }}">{{ AcquistiService::stringa_mese(intval($item->month)) }}
                        </option>
                    @endforeach
                </select>
                <label for="floatingSelect">Mese</label>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Data</th>
                        <th>Operazioni</th>
                    </tr>
                </thead>

                <tbody id="tabella-body">
                    <!-- riempita via JS -->
                </tbody>
            </table>
        @else
            <h3>Non ci sono ordini!</h3>
        @endif
    @endsection
