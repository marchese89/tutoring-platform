@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Modifica credenziali" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.credentials-settings :email="$email" :email-action="route('admin.account.email.update')"
            :password-action="route('admin.account.password.update')"
            password-description="Aggiorna la password dell'account amministratore." />
    </x-ui.page-section>
@endsection
