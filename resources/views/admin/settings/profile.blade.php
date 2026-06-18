@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.settings.personal_data_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item
                :title="__('admin.settings.photo_title')"
                :text="__('admin.settings.photo_text')"
                :url="route('admin.account.photo')"
                icon="fa-solid fa-image"
            />

            <x-ui.card-item
                :title="__('admin.settings.address_title')"
                :text="__('admin.settings.address_text')"
                :url="route('admin.account.address')"
                icon="fa-solid fa-location-dot"
            />

            <x-ui.card-item
                :title="__('admin.settings.certificates_title')"
                :text="__('admin.settings.certificates_text')"
                :url="route('admin.account.certificates.index')"
                icon="fa-solid fa-certificate"
            />

            <x-ui.card-item
                :title="__('admin.settings.vat_title')"
                :text="__('admin.settings.vat_text')"
                :url="route('admin.account.vat-number')"
                icon="fa-solid fa-file-invoice-dollar"
            />
        </div>
    </x-ui.page-section>
@endsection
