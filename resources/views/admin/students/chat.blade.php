@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Chat con studente" />
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
                        Presentazione
                    </h5>

                    <x-ui.pdf-viewer :src="'/protected-files/' . $presentationFile" title="Presentazione" />
                </div>
            @endif

            @if ($contentFile)
                <div>
                    <h5 class="fw-semibold mb-3">
                        Svolgimento
                    </h5>

                    <x-ui.pdf-viewer :src="'/protected-files/' . $contentFile" title="Svolgimento" />
                </div>
            @endif
        </x-ui.card>

        <x-ui.support-chat :chat="$chat" :messages="$messages" :post-route="route('admin.chat.messages.store')" :own-author="\App\Enums\ChatSenderRole::ADMIN->value" own-sender="Tu"
            :other-sender="$studentName" title="Conversazione" description="Gestisci qui i messaggi con lo studente." />
    </x-ui.page-section>
@endsection
