@extends('layouts.theme-areas-layout')

@section('page-title')
    <div class="container py-5">
        <h3 class="fw-bold display-6">
            {{ __('public.catalog.exercise_trace_title') }}
        </h3>
    </div>
@endsection

@section('inner')
    <div class="container py-5">
        <x-ui.document-preview :badge="__('public.catalog.exercise_trace_title')" badge-class="bg-warning text-dark" :course-name="$course->name"
            :description="__('public.catalog.exercise_trace_description')" :title-label="__('public.catalog.exercise_title_label')" :title="$exercise->title"
            :section-title="__('public.catalog.exercise_trace_section')" :file-path="$exercise->prompt_file" />

    </div>
@endsection
