@extends('layouts.student-dashboard')

@section('inner')
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.dashboard') }}">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="{{ route('student.orders.index') }}">Ordini</a>
        </li>
    </ul>
    <div class="row g-0 container-fluid" style="text-align: center">
        @php
            use App\Models\Order;
            use App\Models\OrderProduct;

            $ordine = Order::where('id', '=', request('id'))->first();
        @endphp
        <h3>Ordine #{{ request('id') }}</h3>
        <h4>{{ $ordine->date }}</h4>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tipo Prodotto</th>
                    <th scope="col">Prezzo</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $prodotti = OrderProduct::where('id_ordine', '=', request('id'))->get();
                    $tot = 0;
                @endphp
                @foreach ($prodotti as $item)
                    <tr>

                        <th scope="row">{{ $item->id_prodotto }}</th>
                        <td>
                            @php
                                if ($item->tipo_prodotto == 0) {
                                    echo 'lezione';
                                }
                                if ($item->tipo_prodotto == 2) {
                                    echo 'esercizio';
                                }
                                if ($item->tipo_prodotto == 5) {
                                    echo 'lezione su richiesta';
                                }
                            @endphp
                        </td>
                        <td>
                            @php
                                $tot += $item->price;
                            @endphp
                            {{ $item->price }}<strong>&euro;</strong>
                        </td>

                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"><strong>Totale: {{ $tot }}&euro;</strong></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <button class="btn btn-primary"
                            onclick="location.href='{{ route('student.invoices.show', request('id')) }}'">Visualizza
                            Fattura</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
