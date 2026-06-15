@extends('layouts.theme-areas-layout')

@section('page-title')
    <x-ui.page-header>
        {{ __('public.catalog.subjects') }}
    </x-ui.page-header>
@endsection

@section('inner')
    <div class="container pb-5">

        <div class="row g-4">

            @foreach ($subjects as $item)
                <x-ui.card-item :title="$item->name" :text="__('public.catalog.subject_text')"
                    :url="route('courses.index', $item->id)" :button="__('public.catalog.subject_action')" />
            @endforeach

        </div>

    </div>
@endsection
