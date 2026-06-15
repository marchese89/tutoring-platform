@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('account.credentials.page_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.credentials-settings :email="$email" :email-action="route('admin.account.email.update')"
            :password-action="route('admin.account.password.update')"
            :password-description="__('account.credentials.admin_password_description')" />
    </x-ui.page-section>
@endsection
