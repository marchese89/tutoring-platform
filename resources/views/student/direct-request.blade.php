@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Richiesta lezione'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card>
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                        Richiesta lezione
                    </span>

                    <h3 class="fw-bold mb-0">
                        {{ $richiesta->title }}
                    </h3>
                </div>

                <div class="text-lg-end">
                    <span
                        class="badge {{ $richiesta->paid ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3 py-2 mb-2">
                        {{ $richiesta->paid ? 'Acquistata' : 'Da acquistare' }}
                    </span>

                    @if ($richiesta->price)
                        <h5 class="fw-semibold mb-0">
                            {{ number_format($richiesta->price, 2, ',', '.') }}&euro;
                        </h5>
                    @endif
                </div>
            </div>
        </x-ui.card>

        <div class="mt-4">
            <x-ui.card>
                <h4 class="fw-bold mb-3">
                    Traccia
                </h4>

                <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                    <iframe src="/protected-files/{{ $richiesta->trace }}#view=FitH"></iframe>
                </div>
            </x-ui.card>
        </div>

        @if ($richiesta->price != null && $richiesta->price != 0 && $richiesta->paid == 0)
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

                        <x-ui.primary-button href="{{ route('cart.items.store', ['id' => $richiesta->id, 'type' => 5]) }}">
                            Acquista
                        </x-ui.primary-button>
                    </div>
                </x-ui.card>
            </div>
        @endif

        @if ($richiesta->paid == 1)
            <div class="mt-4">
                <x-ui.card>
                    <h4 class="fw-bold mb-3">
                        Soluzione
                    </h4>

                    <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                        <iframe src="/protected-files/{{ $richiesta->execution }}#view=FitH"></iframe>
                    </div>
                </x-ui.card>
            </div>

            @if ($chat)
                <div class="mt-4">
                    <x-ui.support-chat
                        :chat="$chat"
                        :messages="$messaggi"
                        description="Scrivi qui per ricevere supporto sulla richiesta."
                    />
                </div>
            @endif
        @endif
    </x-ui.page-section>
@endsection
