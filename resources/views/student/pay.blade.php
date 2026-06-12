@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Acquista'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.stripe-payment-form :formatted-total="$formattedTotal" :stripe-key="$stripeKey" :intent-url="route('payment.extra.intent')" :return-url="route('payment.success')"
                    :back-url="route('payment.extra')" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
