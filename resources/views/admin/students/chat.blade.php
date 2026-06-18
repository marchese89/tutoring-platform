@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.students.chat_with_student')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card class="mb-4">
            <h3 class="fw-bold mb-4">
                {{ $title }}
            </h3>

            @if ($presentationFile)
                <div class="mb-5">
                    <h5 class="fw-semibold mb-3">
                        {{ $presentationLabel }}
                    </h5>

                    <x-ui.pdf-viewer :src="'/protected-files/' . $presentationFile" :title="$presentationLabel" />
                </div>
            @endif

            @if ($contentFile)
                <div>
                    <h5 class="fw-semibold mb-3">
                        {{ $contentLabel }}
                    </h5>

                    <x-ui.pdf-viewer :src="'/protected-files/' . $contentFile" :title="$contentLabel" />
                </div>
            @endif
        </x-ui.card>

        <x-ui.support-chat :chat="$chat" :messages="$messages" :post-route="route('admin.chat.messages.store')" :own-author="\App\Enums\ChatSenderRole::ADMIN->value" :own-sender="__('admin.students.you')"
            :other-sender="$studentName" :title="__('admin.students.conversation')" :description="__('admin.students.conversation_text')" />
    </x-ui.page-section>
@endsection
