@extends('layouts.layout-bootstrap')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <h4 class="mb-4 text-center">{{ __('auth.register.title') }}</h4>

                        <form method="POST" action="{{ route('register.store') }}">
                            @csrf

                            <div class="row g-3">

                                {{-- Personal details --}}
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('auth.fields.first_name') }}</label>
                                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                                        class="form-control @error('first_name') is-invalid @enderror">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('auth.fields.last_name') }}</label>
                                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                                        class="form-control @error('last_name') is-invalid @enderror">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">{{ __('auth.fields.address') }}</label>
                                    <input type="text" name="address" value="{{ old('address') }}"
                                        class="form-control @error('address') is-invalid @enderror">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('auth.fields.house_number') }}</label>
                                    <input type="text" name="house_number" value="{{ old('house_number') }}"
                                        class="form-control @error('house_number') is-invalid @enderror">
                                    @error('house_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('auth.fields.city') }}</label>
                                    <input type="text" name="city" value="{{ old('city') }}"
                                        class="form-control @error('city') is-invalid @enderror">
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label">{{ __('auth.fields.province') }}</label>
                                    <input type="text" name="province" value="{{ old('province') }}"
                                        class="form-control @error('province') is-invalid @enderror">
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('auth.fields.postal_code') }}</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                        class="form-control @error('postal_code') is-invalid @enderror">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('auth.fields.tax_code') }}</label>
                                    <input type="text" name="tax_code" value="{{ old('tax_code') }}"
                                        class="form-control @error('tax_code') is-invalid @enderror">
                                    @error('tax_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- EMAIL BLOCK --}}
                                <div class="col-12 mt-2">
                                    <hr>
                                    <small class="text-muted">{{ __('auth.register.access_details') }}</small>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('auth.fields.email') }}</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('auth.fields.email_confirmation') }}</label>
                                    <input type="email" name="email_confirmation" value="{{ old('email_confirmation') }}"
                                        class="form-control @error('email_confirmation') is-invalid @enderror">
                                    @error('email_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- PASSWORD BLOCK --}}
                                <div class="col-md-6">
                                    <x-ui.password-field name="password" :label="__('auth.fields.password')" wrapper-class="mb-0"
                                        autocomplete="new-password" required />
                                </div>

                                <div class="col-md-6">
                                    <x-ui.password-field name="password_confirmation" :label="__('auth.fields.password_confirmation')"
                                        wrapper-class="mb-0" autocomplete="new-password" required />
                                </div>

                                <div class="col-12 text-center mt-4">
                                    <button class="btn btn-primary px-5">
                                        {{ __('auth.register.submit') }}
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
