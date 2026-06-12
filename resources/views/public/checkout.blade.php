@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.stripe-payment-form :formatted-total="$formattedTotal" :stripe-key="$stripeKey" :intent-url="route('payment.process')" :return-url="route('payment.success')"
                    :back-url="route('cart.show')" />
            </div>
        </div>
    </x-ui.page-section>
@endsection
