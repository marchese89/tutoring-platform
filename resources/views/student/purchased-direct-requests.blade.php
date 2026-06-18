@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.requests.purchased_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-table :title="__('student.requests.lessons_title')" :requests="$purchasedDirectRequests"
            :empty-text="__('student.requests.purchased_empty')" />
    </x-ui.page-section>
@endsection
