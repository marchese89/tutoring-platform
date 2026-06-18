@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('account.address.page_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.address-form :action="route('admin.account.address.update')" :values="$address"
            :description="__('account.address.admin_description')" />
    </x-ui.page-section>
@endsection
