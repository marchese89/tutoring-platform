@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <x-ui.form-card :title="__('auth.password_reset.request_title')" icon="bi-envelope">
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <x-ui.form-field type="email" name="email" :label="__('auth.fields.email')" maxlength="255"
                            :value="old('email')" autocomplete="email" required />

                        <x-ui.primary-button type="submit" class="w-100 justify-content-center">
                            {{ __('auth.password_reset.request_submit') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
