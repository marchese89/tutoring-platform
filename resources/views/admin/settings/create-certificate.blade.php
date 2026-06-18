@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.settings.add_certificate_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <x-ui.form-card
                    :title="__('admin.settings.certificate_file_title')"
                    :description="__('admin.settings.certificate_file_description')"
                    icon="bi-file-earmark-arrow-up">
                    <div class="mb-4">
                        @if ($uploadedCertificateFile)
                            <x-ui.pdf-viewer :src="$uploadedCertificateFile" :title="__('admin.settings.certificate_preview_title')" size="compact" />
                        @else
                            <x-ui.empty-state :title="__('admin.settings.certificate_file_empty_title')"
                                :text="__('admin.settings.certificate_file_empty_text')" />
                        @endif
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.account.certificates.uploads.store') }}"
                        enctype="multipart/form-data"
                        class="mb-4"
                        data-upload-progress-form>
                        @csrf

                        <x-ui.form-file :label="__('admin.settings.select_file')" accept="application/pdf" required />

                        <x-ui.upload-progress :label="__('admin.settings.certificate_upload_progress')" />

                        <x-ui.primary-button type="submit">
                            {{ __('admin.settings.upload_file') }}
                        </x-ui.primary-button>
                    </form>

                    @if ($uploadedCertificateFile)
                        <form method="POST" action="{{ route('admin.account.certificates.uploads.destroy') }}">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                {{ __('admin.settings.delete_file') }}
                            </button>
                        </form>
                    @endif
                </x-ui.form-card>

                @if ($uploadedCertificateFile)
                    <x-ui.form-card
                        class="mt-4"
                        :title="__('admin.settings.certificate_details_title')"
                        :description="__('admin.settings.certificate_details_text')"
                        icon="bi-award">
                        <form method="POST" action="{{ route('admin.account.certificates.store') }}">
                            @csrf

                            <x-ui.form-field
                                name="name"
                                :label="__('admin.settings.certificate_name')"
                                maxlength="255"
                                :value="old('name')" />

                            <x-ui.primary-button type="submit">
                                {{ __('admin.settings.add_certificate') }}
                            </x-ui.primary-button>
                        </form>
                    </x-ui.form-card>
                @endif
            </div>
        </div>
    </x-ui.page-section>
@endsection
