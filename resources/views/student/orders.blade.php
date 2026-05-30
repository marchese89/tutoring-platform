@extends('layouts.student-dashboard')

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
            const yearSelect = document.getElementById('floatingSelect1');
            const monthSelect = document.getElementById('floatingSelect2');

            if (!yearSelect || !monthSelect) {
                return;
            }

            aggiorna_tabella(yearSelect.value, monthSelect.value);
        }
    </script>

    <div class="row g-0 container-fluid" style="text-align: center">
        <h3>Ordini Effettuati</h3>

        @if ($hasOrders)
            <div class="form-floating" style="display: inline">
                <select class="form-select" id="floatingSelect1" aria-label="Floating label select example"
                    onchange="aggiorna_tabella(
                        document.getElementById('floatingSelect1').value,
                        document.getElementById('floatingSelect2').value
                    )">
                    <option selected value="{{ $selectedYear }}">{{ $selectedYear }}</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
                <label for="floatingSelect">Anno</label>
            </div>
            <div class="form-floating" style="display: inline">
                <select class="form-select" id="floatingSelect2" aria-label="Floating label select example"
                    onchange="aggiorna_tabella(
                        document.getElementById('floatingSelect1').value,
                        document.getElementById('floatingSelect2').value
                    )">
                    <option selected value="{{ $selectedMonth }}">
                        {{ $selectedMonthLabel }}
                    </option>
                    @foreach ($months as $month)
                        <option value="{{ $month['value'] }}">
                            {{ $month['label'] }}
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
    </div>
@endsection
