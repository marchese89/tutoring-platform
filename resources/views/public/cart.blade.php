@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="text-center mb-4">
                    <h2 class="fw-bold mb-2">Carrello</h2>
                </div>

                @if (count($items) === 0)
                    <x-ui.card class="h-auto">
                        <x-ui.empty-state title="Il carrello è vuoto"
                            text="Al momento non ci sono articoli nel carrello." />
                    </x-ui.card>
                @else
                    <x-ui.table-card>
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Titolo</th>
                                    <th scope="col">Prezzo</th>
                                    <th scope="col" class="text-end">Azioni</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <th scope="row">{{ $item['id'] }}</th>
                                        <td>{{ $item['name'] }}</td>
                                        <td>{{ $item['price'] }} &euro;</td>
                                        <td class="text-end">
                                            <form id="form-remove-{{ $item['id'] }}-{{ $item['type'] }}" method="POST"
                                                action="{{ $item['remove_url'] }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                                    <i class="bi bi-trash me-1"></i>
                                                    Rimuovi
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4 pt-4 border-top">
                            <h3 class="mb-0">
                                Totale: {{ $total }} &euro;
                            </h3>

                            <x-ui.primary-button href="{{ route('checkout.show') }}">
                                Procedi al pagamento
                            </x-ui.primary-button>
                        </div>
                    </x-ui.table-card>
                @endif
            </div>
        </div>
    </x-ui.page-section>
@endsection
