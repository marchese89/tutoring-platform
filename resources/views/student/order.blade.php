@extends('layouts.student-dashboard')

@section('inner')
    <div class="container" style="text-align: center">
        <h3>Ordine #{{ $ordine->id }}</h3>
        <h4>{{ $orderDate }}</h4>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tipo Prodotto</th>
                    <th scope="col">Prezzo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($prodotti as $item)
                    <tr>

                        <th scope="row">{{ $item['id'] }}</th>
                        <td>
                            {{ $item['type'] }}
                        </td>
                        <td>
                            {{ $item['price'] }}<strong>&euro;</strong>
                        </td>

                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"><strong>Totale: {{ $tot_ordine }}&euro;</strong></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <button class="btn btn-primary"
                            onclick="location.href='{{ route('student.invoices.show', $ordine->id) }}'">Visualizza
                            Fattura</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
