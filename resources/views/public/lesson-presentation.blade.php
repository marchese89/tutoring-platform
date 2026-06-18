@extends('layouts.theme-areas-layout')

@section('page-title')
    <div class="container py-4">
        <h3 class="fw-bold">
            {{ __('public.catalog.lesson_preview_title') }}
        </h3>

    </div>
@endsection

@section('inner')
    <div class="container py-4">

        <x-ui.document-preview :badge="__('public.catalog.lesson_preview_title')" :course-name="$course->name"
            :description="__('public.catalog.lesson_preview_description')" :title-label="__('public.catalog.lesson_title_label')" :title="$lesson->title"
            :section-title="__('public.catalog.lesson_presentation_section')" :file-path="$lesson->presentation_file" />
    </div>
@endsection
