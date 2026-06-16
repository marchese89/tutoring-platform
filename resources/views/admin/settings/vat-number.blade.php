@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.settings.vat_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <x-ui.form-card
                    :title="__('admin.settings.vat_number')"
                    :description="__('admin.settings.vat_description')"
                    icon="bi-receipt">
                    <form method="POST" action="{{ route('admin.account.vat-number.update') }}">
                        @csrf

                        <x-ui.form-field
                            name="vat_number"
                            :label="__('admin.settings.vat_number')"
                            minlength="11"
                            maxlength="11"
                            :value="old('vat_number', $vatNumber)" />

                        <x-ui.primary-button type="submit" class="w-100 justify-content-center">
                            {{ __('admin.settings.save') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
