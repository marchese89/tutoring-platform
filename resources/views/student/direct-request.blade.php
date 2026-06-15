@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Richiesta lezione'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.lesson-request-summary :title="$lessonRequest->title"
            :status-label="$lessonRequest->is_paid ? 'Acquistata' : 'Da acquistare'"
            :status-variant="$lessonRequest->is_paid ? 'success' : 'warning'" :price="$lessonRequest->price" />

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    Traccia
                </h4>

                <x-ui.pdf-viewer :src="'/protected-files/' . $lessonRequest->request_file" title="Richiesta dello studente" />
            </x-ui.card>
        </div>

        @if ($lessonRequest->price !== null && $lessonRequest->price > 0 && ! $lessonRequest->is_paid)
            <div class="mt-4">
                <x-ui.card>
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h4 class="fw-bold mb-1">
                                Soluzione disponibile
                            </h4>

                            <p class="text-muted mb-0">
                                Acquista la richiesta per accedere allo svolgimento e alla chat di supporto.
                            </p>
                        </div>

                        <form method="POST"
                            action="{{ route('cart.items.store', ['id' => $lessonRequest->id, 'type' => 5]) }}">
                            @csrf
                            <x-ui.primary-button type="submit">
                                Acquista
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
                        Soluzione
                    </h4>

                    <x-ui.pdf-viewer :src="'/protected-files/' . $lessonRequest->solution_file" title="Soluzione" />
                </x-ui.card>
            </div>

            @if ($chat)
                <div class="mt-4">
                    <x-ui.support-chat
                        :chat="$chat"
                        :messages="$messages"
                        description="Scrivi qui per ricevere supporto sulla richiesta."
                    />
                </div>
            @endif
        @endif
    </x-ui.page-section>
@endsection
