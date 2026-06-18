@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.payment.extra_title')" />
@endsection

@section('inner')
    <script>
        function updatePaymentTotal() {
            const price = Number(document.getElementById('price').value.replace(',', '.')) || 0;
            const quantity = Number(document.getElementById('quantity').value.replace(',', '.')) || 0;
            const total = price * quantity;

            document.getElementById('payment-total').innerHTML = `${total.toFixed(2)}&euro;`;
        }

        window.addEventListener('DOMContentLoaded', updatePaymentTotal);
    </script>

    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <x-ui.card>
                    <div class="mb-4">
                        <h4 class="fw-bold mb-2">
                            {{ __('student.payment.details_title') }}
                        </h4>

                        <p class="text-muted mb-0">
                            {{ __('student.payment.details_text') }}
                        </p>
                    </div>

                    @if (session()->has('error'))
                        <div class="alert alert-danger rounded-4">
                            {{ session()->get('error') }}
                        </div>
                    @endif

                    <form class="row g-3" method="POST" action="{{ route('checkout.payment.prepare') }}">
                        @csrf

                        <div class="col-md-8">
                            <label class="form-label fw-semibold" for="description">
                                {{ __('student.payment.description') }}
                            </label>
                            <input
                                type="text"
                                class="form-control rounded-3 @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                value="{{ old('description') }}"
                                maxlength="255"
                                placeholder="{{ __('student.payment.description_placeholder') }}"
                            >
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold" for="price">
                                {{ __('student.payment.price') }}
                            </label>
                            <input
                                type="text"
                                class="form-control rounded-3 @error('price') is-invalid @enderror"
                                id="price"
                                name="price"
                                value="{{ old('price') }}"
                                maxlength="12"
                                inputmode="decimal"
                                oninput="updatePaymentTotal()"
                            >
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold" for="quantity">
                                {{ __('student.payment.quantity') }}
                            </label>
                            <input
                                type="text"
                                class="form-control rounded-3 @error('quantity') is-invalid @enderror"
                                id="quantity"
                                name="quantity"
                                value="{{ old('quantity') }}"
                                maxlength="5"
                                inputmode="numeric"
                                oninput="updatePaymentTotal()"
                            >
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="border rounded-4 p-3 bg-light d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                                <span class="text-muted">
                                    {{ __('student.payment.total') }}
                                </span>

                                <span class="fs-4 fw-bold text-success" id="payment-total">
                                    0.00&euro;
                                </span>
                            </div>
                        </div>

                        <div class="col-12 text-end">
                            <x-ui.primary-button type="submit">
                                {{ __('student.payment.pay') }}
                            </x-ui.primary-button>
                        </div>
                    </form>
                </x-ui.card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
