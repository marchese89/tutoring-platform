@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.students.request_show_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-summary :title="$lessonRequest->title"
            :status-label="$lessonRequest->is_fulfilled ? __('admin.students.request_completed') : __('admin.students.request_pending')"
            :status-variant="$lessonRequest->is_fulfilled ? 'success' : 'warning'" :price="$lessonRequest->price" />

        <div class="row g-4 mt-0">
            <div class="col-12">
                <x-ui.card>
                    <h4 class="fw-bold mb-3">{{ __('admin.students.prompt') }}</h4>

                    <x-ui.pdf-viewer :src="'/protected-files/' . $lessonRequest->request_file" :title="__('admin.students.student_request')" />
                </x-ui.card>
            </div>

            @if ($lessonRequest->solution_file)
                <div class="col-12">
                    <x-ui.card>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                            <h4 class="fw-bold mb-0">{{ __('admin.students.solution') }}</h4>

                            @if (! $lessonRequest->is_fulfilled)
                                <form action="{{ route('admin.lesson-requests.solution.destroy', $lessonRequest->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger">
                                        {{ __('admin.students.delete_solution') }}
                                    </button>
                                </form>
                            @endif
                        </div>

                        <x-ui.pdf-viewer :src="'/protected-files/' . $lessonRequest->solution_file" :title="__('admin.students.solution')" />
                    </x-ui.card>
                </div>
            @endif

            <div class="col-lg-7">
                <x-ui.form-card :title="__('admin.students.upload_solution_title')" :description="__('admin.students.upload_solution_text')"
                    icon="bi-file-earmark-arrow-up">
                    <form method="POST"
                        action="{{ route('admin.lesson-requests.solution.store', $lessonRequest->id) }}"
                        enctype="multipart/form-data" data-upload-progress-form>
                        @csrf

                        <x-ui.form-file id="solution-file" :label="__('admin.students.pdf_file')" accept="application/pdf" required />

                        <x-ui.upload-progress :label="__('admin.students.upload_solution_progress')" />

                        <x-ui.primary-button type="submit">
                            {{ __('admin.students.upload_solution') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>

            <div class="col-lg-5">
                <x-ui.form-card :title="__('admin.students.set_price_title')" :description="__('admin.students.set_price_text')"
                    icon="bi-currency-euro">
                    <form action="{{ route('admin.lesson-requests.price.store', $lessonRequest->id) }}" method="POST">
                        @csrf

                        <x-ui.form-field name="price" :label="__('admin.students.price')" type="number"
                            :value="old('price', $lessonRequest->price)" min="0" step="0.01" required />

                        <x-ui.primary-button type="submit">
                            {{ __('admin.students.save_price') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
