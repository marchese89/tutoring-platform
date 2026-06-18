@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <x-ui.form-card :title="__('auth.password_reset.reset_title')" icon="bi-lock">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <x-ui.form-field type="email" name="email" :label="__('auth.fields.email')"
                            autocomplete="email" required />

                        <x-ui.password-field name="password" :label="__('auth.fields.new_password')"
                            autocomplete="new-password" required />

                        <x-ui.password-field name="password_confirmation"
                            :label="__('auth.fields.password_confirmation')" autocomplete="new-password" required />

                        <x-ui.primary-button type="submit" class="w-100 justify-content-center">
                            {{ __('auth.password_reset.reset_submit') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
