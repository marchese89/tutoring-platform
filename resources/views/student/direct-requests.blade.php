@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.requests.direct_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-table :title="__('student.requests.unpurchased_title')" :requests="$directRequests"
            :empty-text="__('student.requests.unpurchased_empty')" />
    </x-ui.page-section>
@endsection
