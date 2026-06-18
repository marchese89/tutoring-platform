@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.feedback-card :title="__('public.payment.complete_title')" :text="__('public.payment.complete_text')"
                    :action-url="route('student.orders.index')" :action-label="__('public.payment.view_orders')" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
