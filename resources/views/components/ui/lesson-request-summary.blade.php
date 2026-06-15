@props([
    'title',
    'statusLabel',
    'statusVariant',
    'price' => null,
])

<x-ui.card>
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
        <div>
            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                Richiesta lezione
            </span>

            <h3 class="fw-bold mb-0">
                {{ $title }}
            </h3>
        </div>

        <div class="text-lg-end">
            <span class="badge bg-{{ $statusVariant }}-subtle text-{{ $statusVariant }} rounded-pill px-3 py-2 mb-2">
                {{ $statusLabel }}
            </span>

            @if ($price !== null)
                <h5 class="fw-semibold mb-0">
                    {{ number_format($price, 2, ',', '.') }}&euro;
                </h5>
            @endif
        </div>
    </div>
</x-ui.card>
