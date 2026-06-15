@extends('layouts.theme-areas-layout')

@section('page-title')
    <x-ui.page-header>
        {{ __('public.catalog.courses') }}
    </x-ui.page-header>
@endsection

@section('inner')
    <div class="container pb-5">

        <div class="row g-4">

            @foreach ($courses as $item)
                <x-ui.card-item :title="$item->name" :text="__('public.catalog.course_text')"
                    :url="route('courses.show', $item->id)" :button="__('public.catalog.course_action')" />
            @endforeach

        </div>

    </div>
@endsection
