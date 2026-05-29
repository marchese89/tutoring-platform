@extends('layouts.theme-areas-layout')

@section('page-title')
    <x-ui.page-header>
        Corsi
    </x-ui.page-header>
@endsection

@section('inner')
    <div class="container pb-5">

        <div class="row g-4">

            @foreach ($corsi as $item)
                <x-ui.card-item :title="$item->name" text="Percorso formativo disponibile nella materia selezionata"
                    :url="route('courses.show', $item->id)" button="Vai" />
            @endforeach

        </div>

    </div>
@endsection
