@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.new_lesson')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <h2 class="h4 fw-bold text-center mb-4">
                    {{ __('admin.teaching.course_label', ['name' => $course->name]) }}
                </h2>

                <div class="mb-4">
                    <x-ui.document-upload-card :title="__('admin.teaching.document_presentation')" :preview-url="$presentationUrl" :upload-url="route('admin.lessons.upload-presentation.store')"
                        input-name="presentation_file" :progress-label="__('admin.teaching.upload_presentation_progress')" :delete-url="$presentationUploaded ? route('admin.lessons.upload-presentation.destroy') : null"
                        :hidden-fields="['course_id' => $id]" />
                </div>

                @if ($presentationUploaded)
                    <div class="mb-4">
                        <x-ui.document-upload-card :title="__('admin.teaching.document_content')" :preview-url="$contentUrl" :upload-url="route('admin.lessons.upload-file.store')"
                            input-name="content_file" :progress-label="__('admin.teaching.upload_content_progress')" :delete-url="$contentUploaded ? route('admin.lessons.upload-file.destroy') : null"
                            :hidden-fields="['course_id' => $id]" />
                    </div>
                @endif

                @if ($presentationUploaded && $contentUploaded)
                    <x-ui.form-card :title="__('admin.teaching.lesson_details')"
                        :description="__('admin.teaching.lesson_details_create_description')" icon="bi-journal-plus">
                        <form method="POST" action="{{ route('admin.lessons.store') }}">
                            @csrf

                            <input type="hidden" name="course_id" value="{{ $id }}">

                            <x-ui.form-field name="number" :label="__('admin.teaching.number')" type="number" min="1"
                                :value="old('number')" />

                            <x-ui.form-field name="title" :label="__('admin.teaching.title_field')" maxlength="255" :value="old('title')" />

                            <x-ui.form-field name="price" :label="__('admin.teaching.price_field')" type="number" min="0" step="0.01"
                                :value="old('price')" />

                            <x-ui.primary-button type="submit">
                                {{ __('admin.teaching.add_lesson') }}
                            </x-ui.primary-button>
                        </form>
                    </x-ui.form-card>
                @endif
            </div>
        </div>
    </x-ui.page-section>
@endsection
