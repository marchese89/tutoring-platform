@extends('layouts.layout-bootstrap')

@section('content')
    <div class="container" style="text-align: center;width:35%">
        <h2>Carrello</h2>
    </div>
    <br>
    <div class="container" style="text-align: center;width:80%; height:800px">

        @php
            $items = session()->get('carrello')->contenuto();
        @endphp
        @if (count($items) == 0)
            <br>
            <h3>Carrello Vuoto!</h3>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Titolo</th>
                        <th scope="col">Prezzo</th>
                        <th scope="col">Operazioni</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($items as $item)
                        <tr>

                            <th scope="row">{{ $item->getId() }}</th>
                            <td>
                                {{ $item->getNome() }}
                            </td>
                            <td>
                                {{ $item->getPrezzo() }} &nbsp;<strong>&euro;</strong>
                            </td>
                            <td>
                                <form id="form-remove-{{ $item->getId() }}-{{ $item->getTipoElemento() }}" method="POST"
                                    style="display: inline"
                                    action="{{ route('cart.items.destroy', ['id' => $item->getId(), 'type' => $item->getTipoElemento()]) }}"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-primary">Rimuovi</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="container">
                <h3>Totale:
                    @php
                        echo session()->get('carrello')->getTotale();
                    @endphp
                    &nbsp;&euro;
                </h3>
            </div>
        @endif
        @if (count($items) != 0)
            <br>
            <br>
            <div>
                <button class="btn btn-primary" onclick="location.href='{{ route('checkout.show') }}'">Acquista</button>
            </div>
            <br>
            <br>
        @endif
    </div>
@endsection
