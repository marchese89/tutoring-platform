@extends('layouts.admin-dashboard')

@push('styles')
    <style>
        .admin-photo-preview {
            width: min(100%, 320px);
            aspect-ratio: 4 / 5;
            object-fit: cover;
            object-position: center;
            border-radius: 8px;
        }
    </style>
@endpush

@section('page-title')
    <x-ui.section-header :title="__('admin.settings.photo_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.form-card
                    :title="__('admin.settings.admin_photo_title')"
                    :description="__('admin.settings.admin_photo_text')"
                    icon="bi-person-circle">
                    @if ($photoPath)
                        <div class="text-center mb-4">
                            <img alt="{{ __('admin.settings.admin_photo_alt') }}" src="{{ $photoPath }}"
                                class="admin-photo-preview shadow-sm border">
                        </div>
                    @else
                        <div class="mb-4">
                            <x-ui.empty-state :title="__('admin.settings.photo_empty_title')"
                                :text="__('admin.settings.photo_empty_text')" />
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('admin.account.photo.update') }}"
                        enctype="multipart/form-data"
                        id="upload"
                        data-upload-progress-form>
                        @csrf

                        <x-ui.form-file :label="__('admin.settings.select_image')" accept="image/jpeg,image/png,image/webp" required />

                        <x-ui.upload-progress :label="__('admin.settings.photo_upload_progress')" />

                        <x-ui.primary-button
                            type="submit"
                            class="w-100 justify-content-center">
                            {{ __('admin.settings.upload_photo') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
