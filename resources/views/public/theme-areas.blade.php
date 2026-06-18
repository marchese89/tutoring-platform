@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-header>
        {{ __('public.catalog.theme_areas') }}
    </x-ui.page-header>

    <div class="container">


        @if ($themeAreas->isEmpty())
            <x-ui.card>
                <div class="text-center py-5">
                    <h4 class="mb-2">{{ __('public.catalog.theme_area_empty_title') }}</h4>
                    <p class="text-muted mb-0">{{ __('public.catalog.theme_area_empty_text') }}</p>
                </div>
            </x-ui.card>
        @else
            <div class="row g-4">

                @foreach ($themeAreas as $item)
                    <x-ui.card-item :title="$item->name" :text="__('public.catalog.theme_area_text')"
                        :url="route('subjects.index', $item->id)" :button="__('public.catalog.theme_area_action')" />
                @endforeach

            </div>

            <x-ui.pagination :paginator="$themeAreas" />
        @endif

    </div>

@endsection
