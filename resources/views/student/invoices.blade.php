@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Fatture'" />
@endsection

@section('inner')
    <x-ui.page-section>
        @if ($invoices->isNotEmpty())
            <x-ui.table-card title="Fatture disponibili">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Ordine</th>
                            <th scope="col">Data</th>
                            <th scope="col">Operazioni</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($invoices as $item)
                            <tr>
                                <th scope="row">{{ $item['number'] ?? $item['id'] }}</th>
                                <td>
                                    #{{ $item['order_id'] }}
                                </td>
                                <td>
                                    {{ $item['date'] }}
                                </td>
                                <td>
                                    <x-ui.primary-button
                                        href="{{ route('student.invoices.show', $item['order_id']) }}"
                                        size="sm"
                                    >
                                        Visualizza
                                    </x-ui.primary-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-ui.table-card>
        @else
            <x-ui.empty-state
                title="Non ci sono fatture"
                text="Quando una fattura sara disponibile, la troverai in questa sezione."
            />
        @endif
    </x-ui.page-section>
@endsection
