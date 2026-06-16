@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item :title="__('admin.teaching.theme_areas_title')"
                :text="__('admin.teaching.theme_areas_text')" :url="route('admin.theme-areas.index')"
                icon="fa-solid fa-layer-group" />

            <x-ui.card-item :title="__('admin.teaching.subjects_title')" :text="__('admin.teaching.subjects_text')"
                :url="route('admin.subjects.index')" icon="fa-solid fa-book" />

            <x-ui.card-item :title="__('admin.teaching.new_course_title')" :text="__('admin.teaching.new_course_text')"
                :url="route('admin.courses.create')" icon="fa-solid fa-graduation-cap" />

            <x-ui.card-item :title="__('admin.teaching.course_list_title')" :text="__('admin.teaching.course_list_text')"
                :url="route('admin.courses.index')" icon="fa-solid fa-list" />
        </div>
    </x-ui.page-section>
@endsection
