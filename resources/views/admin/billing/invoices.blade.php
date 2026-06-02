@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Elenco Fatture'" />
@endsection

@section('inner')
    <x-ui.page-section>

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
                                    {{ $item->date }}
                                </td>

                                <td>
                                    @if ($item->showUrl)
                                        <x-ui.primary-button href="{{ $item->showUrl }}">
                                            Visualizza
                                        </x-ui.primary-button>
                                    @else
                                        <span class="text-muted">Non disponibile</span>
                                    @endif
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
