@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Modifica credenziali" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.credentials-settings :email="$email" :email-action="route('student.account.email.update')"
            :password-action="route('student.account.password.update')"
            password-description="Scegli una password robusta per proteggere l'account." />
    </x-ui.page-section>
@endsection
