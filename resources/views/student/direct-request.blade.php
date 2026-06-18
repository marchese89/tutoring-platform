@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.requests.detail_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-summary :title="$lessonRequest->title"
            :status-label="$lessonRequest->is_paid ? __('student.requests.purchased') : __('student.requests.not_purchased')"
            :status-variant="$lessonRequest->is_paid ? 'success' : 'warning'" :price="$lessonRequest->price" />

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    {{ __('student.requests.prompt') }}
                </h4>

                <x-ui.pdf-viewer :src="'/protected-files/' . $lessonRequest->request_file"
                    :title="__('student.requests.student_request')" />
            </x-ui.card>
        </div>

        @if ($lessonRequest->price !== null && $lessonRequest->price > 0 && ! $lessonRequest->is_paid)
            <div class="mt-4">
                <x-ui.card>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">
                                {{ __('student.requests.solution_available') }}
                            </h4>

                            <p class="text-muted mb-0">
                                {{ __('student.requests.purchase_description') }}
                            </p>
                        </div>

                        <form method="POST"
                            action="{{ route('cart.items.store', ['id' => $lessonRequest->id, 'type' => 5]) }}">
                            @csrf
                            <x-ui.primary-button type="submit">
                                {{ __('student.requests.purchase') }}
                            </x-ui.primary-button>
                        </form>
                    </div>
                </x-ui.card>
            </div>
        @endif

        @if ($lessonRequest->is_paid)
            <div class="mt-4">
                <x-ui.card>
                    <h4 class="fw-bold mb-3">
                        {{ __('student.requests.solution') }}
                    </h4>

                    <x-ui.pdf-viewer :src="'/protected-files/' . $lessonRequest->solution_file"
                        :title="__('student.requests.solution')" />
                </x-ui.card>
            </div>

            @if ($chat)
                <div class="mt-4">
                    <x-ui.support-chat
                        :chat="$chat"
                        :messages="$messages"
                        :description="__('student.requests.chat_description')"
                    />
                </div>
            @endif
        @endif
    </x-ui.page-section>
@endsection
