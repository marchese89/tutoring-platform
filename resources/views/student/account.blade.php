@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.account.title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item :title="__('student.account.personal_title')" :text="__('student.account.personal_text')"
                :url="route('student.account.profile')" icon="fa-solid fa-id-card" />

            <x-ui.card-item :title="__('student.account.credentials_title')"
                :text="__('student.account.credentials_text')" :url="route('student.account.credentials')"
                icon="fa-solid fa-key" />
        </div>
    </x-ui.page-section>
@endsection
