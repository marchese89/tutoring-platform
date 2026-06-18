@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.students.requests_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-table :title="__('admin.students.request_list_title')" :requests="$lessonRequests"
            :empty-text="__('admin.students.request_empty')" />
    </x-ui.page-section>
@endsection
