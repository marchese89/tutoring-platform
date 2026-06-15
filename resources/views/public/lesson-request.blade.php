@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <x-ui.form-card :title="__('public.lesson_request.title')"
                    :description="__('public.lesson_request.description')"
                    icon="bi-file-earmark-text" body-class="p-4 p-lg-5">
                    <div class="mb-5">
                        <p class="text-muted mb-2">
                            {{ __('public.lesson_request.instructions_file') }}
                        </p>
                        <p class="text-muted mb-2">
                            {{ __('public.lesson_request.instructions_price') }}
                        </p>
                        <p class="text-muted mb-0">
                            {{ __('public.lesson_request.instructions_chat') }}
                        </p>
                    </div>

                    @if (! $userCanSubmit)
                        <div class="alert alert-warning rounded-3 mb-0" role="alert">
                            <h5 class="fw-semibold mb-2">
                                {{ __('public.lesson_request.student_required_title') }}
                            </h5>

                            <p class="mb-3">
                                {{ __('public.lesson_request.student_required_text') }}
                            </p>

                            @if (! $isAuthenticated)
                                <x-ui.primary-button href="{{ route('login', ['back' => 1]) }}">
                                    {{ __('public.lesson_request.login') }}
                                </x-ui.primary-button>
                            @endif
                        </div>
                    @elseif (! $uploadedRequestFile)
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <form method="POST" action="{{ route('lesson-requests.files.store') }}"
                                    enctype="multipart/form-data" data-upload-progress-form>
                                    @csrf

                                    <x-ui.form-file :label="__('public.lesson_request.select_file')" accept="application/pdf"
                                        class="form-control-lg" wrapper-class="mb-4" required />

                                    <x-ui.upload-progress :label="__('public.lesson_request.upload_progress')" />

                                    <x-ui.primary-button type="submit" class="w-100 justify-content-center">
                                        {{ __('public.lesson_request.upload') }}
                                    </x-ui.primary-button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center mb-4">
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                {{ __('public.lesson_request.uploaded') }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <x-ui.pdf-viewer :src="$uploadedRequestFileUrl" :title="__('public.lesson_request.preview')"
                                size="compact" />
                        </div>

                        <div class="d-flex justify-content-end mb-4">
                            <form method="POST" action="{{ route('lesson-requests.files.destroy') }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                    {{ __('public.lesson_request.delete_file') }}
                                </button>
                            </form>
                        </div>

                        <hr class="my-4">

                        <form method="POST" action="{{ route('lesson-requests.store') }}">
                            @csrf

                            <x-ui.form-field name="title" :label="__('public.lesson_request.request_title')" maxlength="255"
                                :value="old('title')" :placeholder="__('public.lesson_request.request_placeholder')" required />

                            <x-ui.primary-button type="submit" class="w-100 justify-content-center">
                                {{ __('public.lesson_request.submit') }}
                            </x-ui.primary-button>
                        </form>
                    @endif
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
