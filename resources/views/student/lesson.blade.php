@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Lezione" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card>
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                        Corso
                    </span>

                    <h3 class="fw-bold mb-0">
                        {{ $corso->name }}
                    </h3>
                </div>

                <div class="text-lg-end">
                    <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 mb-2">
                        Lezione
                    </span>

                    <h5 class="fw-semibold mb-0">
                        {{ $lezione->title }}
                    </h5>
                </div>
            </div>
        </x-ui.card>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    Presentazione
                </h4>

                <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                    <iframe src="/protected-files/{{ $lezione->presentation_file }}#view=FitH"></iframe>
                </div>
            </x-ui.card>
        </div>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    Svolgimento
                </h4>

                <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                    <iframe src="/protected-files/{{ $lezione->content_file }}#view=FitH"></iframe>
                </div>
            </x-ui.card>
        </div>

        <div class="mt-4">
            <x-ui.support-chat
                :chat="$chat"
                :messages="$messaggi"
                description="Scrivi qui per ricevere supporto sulla lezione."
            />
        </div>
    </x-ui.page-section>
@endsection
