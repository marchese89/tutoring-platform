@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.settings.account_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item
                :title="__('admin.settings.personal_data_title')"
                :text="__('admin.settings.personal_data_text')"
                :url="route('admin.account.profile')"
                icon="fa-solid fa-user"
            />

            <x-ui.card-item
                :title="__('admin.settings.credentials_title')"
                :text="__('admin.settings.credentials_text')"
                :url="route('admin.account.credentials')"
                icon="fa-solid fa-shield-halved"
            />
        </div>
    </x-ui.page-section>
@endsection
