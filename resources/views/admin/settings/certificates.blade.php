@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.settings.certificates_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="d-flex justify-content-end mb-4">
            <x-ui.primary-button href="{{ route('admin.account.certificates.create') }}">
                {{ __('admin.settings.add_certificate') }}
            </x-ui.primary-button>
        </div>

        <div class="row g-4">
            @forelse ($certificates as $certificate)
                <div class="col-12">
                    <x-ui.form-card
                        :title="__('admin.settings.certificate_title', ['id' => $certificate->id])"
                        :description="__('admin.settings.certificate_description')"
                        icon="bi-award">
                        <div class="d-flex justify-content-end mb-4">
                            <form method="POST" action="{{ route('admin.account.certificates.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $certificate->id }}">

                                <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                                    {{ __('admin.settings.delete') }}
                                </button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('admin.account.certificates.name.update') }}" class="mb-4">
                            @csrf
                            <input type="hidden" name="id" value="{{ $certificate->id }}">

                            <x-ui.form-field
                                name="name"
                                id="name_{{ $certificate->id }}"
                                :label="__('admin.settings.certificate_name')"
                                maxlength="255"
                                :value="old('name', $certificate->name)" />

                            <x-ui.primary-button type="submit" size="sm">
                                {{ __('admin.settings.edit_certificate_name') }}
                            </x-ui.primary-button>
                        </form>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('admin.settings.certificate_file') }}
                            </label>

                            @if ($certificate->file_path)
                                <x-ui.pdf-viewer :src="$certificate->file_path" :title="__('admin.settings.certificate_preview', ['id' => $certificate->id])"
                                    size="compact" />
                            @else
                                <x-ui.empty-state :title="__('admin.settings.certificate_file_empty_title')"
                                    :text="__('admin.settings.certificate_file_empty_text')" />
                            @endif
                        </div>

                        <form
                            method="POST"
                            action="{{ route('admin.account.certificates.file.update') }}"
                            enctype="multipart/form-data"
                            data-upload-progress-form>
                            @csrf

                            <input type="hidden" name="id" value="{{ $certificate->id }}">

                            <x-ui.form-file :label="__('admin.settings.replace_file')" id="file_{{ $certificate->id }}"
                                accept="application/pdf" required />

                            <x-ui.upload-progress :label="__('admin.settings.certificate_upload_progress')" />

                            <x-ui.primary-button type="submit" size="sm">
                                {{ __('admin.settings.upload_file') }}
                            </x-ui.primary-button>
                        </form>
                    </x-ui.form-card>
                </div>
            @empty
                <div class="col-12">
                    <x-ui.empty-state
                        :title="__('admin.settings.certificate_empty_title')"
                        :text="__('admin.settings.certificate_empty_text')" />
                </div>
            @endforelse
        </div>
    </x-ui.page-section>
@endsection
