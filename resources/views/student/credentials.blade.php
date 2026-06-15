@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('account.credentials.page_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.credentials-settings :email="$email" :email-action="route('student.account.email.update')"
            :password-action="route('student.account.password.update')"
            :password-description="__('account.credentials.student_password_description')" />
    </x-ui.page-section>
@endsection
