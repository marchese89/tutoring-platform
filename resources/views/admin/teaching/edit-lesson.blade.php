@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.edit_lesson')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="text-center mb-4">
                    <h2 class="h4 fw-bold mb-2">
                        {{ __('admin.teaching.course_label', ['name' => $course->name]) }}
                    </h2>

                    <p class="text-muted mb-0">
                        {{ __('admin.teaching.lesson_label', ['title' => $lesson->title]) }}
                    </p>
                </div>

                <div class="mb-4">
                    <x-ui.document-upload-card :title="__('admin.teaching.document_presentation')" :preview-url="route('protected-files.show', [
                        'path' => $lesson->presentation_file,
                    ])" :upload-url="route('admin.lessons.presentation.update', $lesson->id)"
                        input-name="presentation_file" :progress-label="__('admin.teaching.upload_presentation_progress')" />
                </div>

                <div class="mb-4">
                    <x-ui.document-upload-card :title="__('admin.teaching.document_content')" :preview-url="route('protected-files.show', [
                        'path' => $lesson->content_file,
                    ])" :upload-url="route('admin.lessons.file.update', $lesson->id)"
                        input-name="content_file" :progress-label="__('admin.teaching.upload_content_progress')" />
                </div>

                <x-ui.form-card :title="__('admin.teaching.lesson_details')" :description="__('admin.teaching.lesson_details_edit_description')"
                    icon="bi-journal-text">
                    <form method="POST" action="{{ route('admin.lessons.update', $lesson->id) }}">
                        @csrf
                        @method('PUT')

                        <x-ui.form-field name="number" :label="__('admin.teaching.number')" type="number" min="1" maxlength="5"
                            :value="old('number', $lesson->number)" />

                        <x-ui.form-field name="title" :label="__('admin.teaching.title_field')" maxlength="255" :value="old('title', $lesson->title)" />

                        <x-ui.form-field name="price" :label="__('admin.teaching.price_field')" type="number" min="0" step="0.01"
                            :value="old('price', $lesson->price)" />

                        <x-ui.primary-button type="submit">
                            {{ __('admin.actions.save_changes') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
