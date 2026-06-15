@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('account.profile.page_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card class="mb-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <span class="text-muted small d-block mb-1">
                        {{ __('account.profile.first_name') }}
                    </span>

                    <div class="fw-semibold fs-5">
                        {{ $name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <span class="text-muted small d-block mb-1">
                        {{ __('account.profile.last_name') }}
                    </span>

                    <div class="fw-semibold fs-5">
                        {{ $surname }}
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.address-form :action="route('student.account.address.update')" :values="$address"
            :description="__('account.address.student_description')" />
    </x-ui.page-section>
@endsection
