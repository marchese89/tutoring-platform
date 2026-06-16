@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.new_exercise')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <h2 class="h4 fw-bold text-center mb-4">
                    {{ __('admin.teaching.course_label', ['name' => $course->name]) }}
                </h2>

                <div class="mb-4">
                    <x-ui.document-upload-card :title="__('admin.teaching.document_prompt')" :preview-url="$promptUrl" :upload-url="route('admin.exercises.trace.upload.store')" input-name="prompt_file"
                        :progress-label="__('admin.teaching.upload_prompt_progress')" :delete-url="$promptUploaded ? route('admin.exercises.trace.session.destroy') : null" :hidden-fields="['course_id' => $id]" />
                </div>

                @if ($promptUploaded)
                    <div class="mb-4">
                        <x-ui.document-upload-card :title="__('admin.teaching.document_content')" :preview-url="$solutionUrl" :upload-url="route('admin.exercises.execution.upload.store')"
                            input-name="solution_file" :progress-label="__('admin.teaching.upload_content_progress')" :delete-url="$solutionUploaded ? route('admin.exercises.execution.session.destroy') : null" />
                    </div>
                @endif

                @if ($promptUploaded && $solutionUploaded)
                    <x-ui.form-card :title="__('admin.teaching.exercise_details')"
                        :description="__('admin.teaching.exercise_details_create_description')" icon="bi-journal-code">
                        <form method="POST" action="{{ route('admin.exercises.store') }}">
                            @csrf

                            <input type="hidden" name="course_id" value="{{ $id }}">

                            <x-ui.form-field name="title" :label="__('admin.teaching.title_field')" maxlength="255" :value="old('title')" />

                            <x-ui.form-field name="price" :label="__('admin.teaching.price_field')" type="number" min="0" step="0.01"
                                :value="old('price')" />

                            <x-ui.primary-button type="submit">
                                {{ __('admin.teaching.add_exercise') }}
                            </x-ui.primary-button>
                        </form>
                    </x-ui.form-card>
                @endif
            </div>
        </div>
    </x-ui.page-section>
@endsection
