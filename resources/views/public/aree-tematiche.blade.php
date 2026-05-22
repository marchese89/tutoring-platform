@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-header>
        Aree Tematiche
    </x-ui.page-header>

    <div class="container">


        @if ($themeAreas->isEmpty())
            <x-ui.card>
                <div class="text-center py-5">
                    <h4 class="mb-2">Nessuna area tematica disponibile</h4>
                    <p class="text-muted mb-0">Le aree verranno pubblicate prossimamente.</p>
                </div>
            </x-ui.card>
        @else
            <div class="row g-4">

                @foreach ($themeAreas as $item)
                    <x-ui.card-item :title="$item->name" text="Scopri tutti i contenuti disponibili per questa area tematica"
                        :url="url('/materie/' . $item->id)" button="Esplora area" />
                @endforeach

            </div>
        @endif

    </div>

@endsection
