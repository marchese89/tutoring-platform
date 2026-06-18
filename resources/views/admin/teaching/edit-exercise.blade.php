@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.edit_exercise')" />
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
                        {{ __('admin.teaching.exercise_label', ['title' => $exercise->title]) }}
                    </p>
                </div>

                <div class="mb-4">
                    <x-ui.document-upload-card :title="__('admin.teaching.document_prompt')" :preview-url="route('protected-files.show', [
                        'path' => $exercise->prompt_file,
                    ])" :upload-url="route('admin.exercises.trace.update', $exercise->id)" input-name="prompt_file"
                        :progress-label="__('admin.teaching.upload_prompt_progress')" />
                </div>

                <div class="mb-4">
                    <x-ui.document-upload-card :title="__('admin.teaching.document_content')" :preview-url="route('protected-files.show', [
                        'path' => $exercise->solution_file,
                    ])" :upload-url="route('admin.exercises.execution.update', $exercise->id)"
                        input-name="solution_file" :progress-label="__('admin.teaching.upload_content_progress')" />
                </div>

                <x-ui.form-card :title="__('admin.teaching.exercise_details')" :description="__('admin.teaching.exercise_details_edit_description')"
                    icon="bi-journal-code">
                    <form method="POST" action="{{ route('admin.exercises.update', $exercise->id) }}">
                        @csrf
                        @method('PUT')

                        <x-ui.form-field name="title" :label="__('admin.teaching.title_field')" maxlength="255" :value="old('title', $exercise->title)" />

                        <x-ui.form-field name="price" :label="__('admin.teaching.price_field')" type="number" min="0" step="0.01"
                            :value="old('price', $exercise->price)" />

                        <x-ui.primary-button type="submit">
                            {{ __('admin.actions.save_changes') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
