@extends('layouts.theme-areas-layout')

@section('page-title')
    <x-ui.page-header>
        {{ __('public.catalog.lesson_page_title') }}
    </x-ui.page-header>
@endsection

@section('inner')
    <x-ui.page-section class="py-5">

        <x-ui.section-header :title="__('public.catalog.lesson_header_title', ['course' => $course->name])" :description="__('public.catalog.lesson_header_description', ['lesson' => $lesson->title])" />

        <x-ui.card>
            <div class="mb-4 text-center">
                <span class="badge bg-primary px-3 py-2 fs-6">
                    {{ __('public.catalog.lesson_page_title') }}
                </span>

                <h3 class="fw-bold mt-3 mb-2">
                    {{ $lesson->title }}
                </h3>

                <p class="text-muted mb-0">
                    {{ __('public.catalog.lesson_online_text') }}
                </p>
            </div>

            <x-ui.pdf-viewer :src="'/protected-files/' . $lesson->content_file" :title="$lesson->title" />
        </x-ui.card>
    </x-ui.page-section>
@endsection
