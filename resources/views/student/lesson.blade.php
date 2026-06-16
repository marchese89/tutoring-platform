@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.courses.lesson')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card>
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                        {{ __('student.courses.course') }}
                    </span>

                    <h3 class="fw-bold mb-0">
                        {{ $course->name }}
                    </h3>
                </div>

                <div class="text-lg-end">
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 mb-2">
                        {{ __('student.courses.lesson') }}
                    </span>

                    <h5 class="fw-semibold mb-0">
                        {{ $lesson->title }}
                    </h5>
                </div>
            </div>
        </x-ui.card>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    {{ __('student.courses.presentation') }}
                </h4>

                <x-ui.pdf-viewer :src="'/protected-files/' . $lesson->presentation_file"
                    :title="__('student.courses.presentation')" />
            </x-ui.card>
        </div>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    {{ __('student.courses.content') }}
                </h4>

                <x-ui.pdf-viewer :src="'/protected-files/' . $lesson->content_file"
                    :title="__('student.courses.lesson_content')" />
            </x-ui.card>
        </div>

        <div class="mt-4">
            <x-ui.support-chat
                :chat="$chat"
                :messages="$messages"
                :description="__('student.courses.lesson_chat')"
            />
        </div>
    </x-ui.page-section>
@endsection
