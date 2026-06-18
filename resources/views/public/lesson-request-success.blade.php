@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.feedback-card :title="__('public.lesson_request.success_title')"
                    :text="__('public.lesson_request.success_text')" :action-url="route('student.dashboard')"
                    :action-label="__('public.lesson_request.back_to_dashboard')" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
