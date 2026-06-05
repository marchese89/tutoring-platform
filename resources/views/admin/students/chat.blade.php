@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Chat Con Studente'" />
@endsection

@section('inner')
    @php
        $enableEcho = true;
        $studentName = trim(($user?->name ?? '') . ' ' . ($user?->surname ?? '')) ?: 'Studente';
    @endphp

    <div class="container">
        <x-ui.card class="mb-4">
            <div class="text-center">
                <h3 class="fw-bold mb-4">
                    {{ $title }}
                </h3>

                <div class="mb-5">
                    <h5 class="fw-semibold mb-3">
                        Presentazione
                    </h5>

                    <iframe class="w-100 rounded-4 border-0" height="700" src="/protected-files/{{ $presentationFile }}#view=FitH">
                    </iframe>
                </div>

                <div>
                    <h5 class="fw-semibold mb-3">
                        Svolgimento
                    </h5>

                    <iframe class="w-100 rounded-4 border-0" height="700" src="/protected-files/{{ $contentFile }}#view=FitH">
                    </iframe>
                </div>
            </div>
        </x-ui.card>

        <x-ui.support-chat
            :chat="$chat"
            :messages="$messages"
            :post-route="route('admin.chat.messages.store')"
            :own-author="1"
            own-sender="Tu"
            :other-sender="$studentName"
            title="Conversazione"
            description="Gestisci qui i messaggi con lo studente."
        />
    </div>
@endsection
