@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.payment.complete_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.feedback-card :title="__('student.payment.invoice_created')"
                    :text="__('student.payment.complete_text')" :action-url="route('student.dashboard')"
                    :action-label="__('student.payment.back_to_dashboard')" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
