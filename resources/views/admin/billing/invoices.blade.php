@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Elenco Fatture'" />
@endsection

@section('inner')
    <x-ui.page-section>

        @php
            use App\Models\Invoice;
            use App\Helpers\DateHelper;
        @endphp

        @if ($fatture->count() > 0)
            <x-ui.table-card title="Fatture">
                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Data</th>
                            <th>Operazioni</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($fatture as $item)
                            <tr>

                                <td>
                                    {{ $item->number }}
                                </td>

                                <td>
                                    {{ DateHelper::format($item->date) }}
                                </td>

                                <td>
                                    <x-ui.primary-button href="{{ route('admin.invoices.show', $item->number) }}">
                                        Visualizza
                                    </x-ui.primary-button>
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </x-ui.table-card>
        @else
            <x-ui.empty-state title="Non ci sono fatture" />
        @endif

    </x-ui.page-section>
@endsection
