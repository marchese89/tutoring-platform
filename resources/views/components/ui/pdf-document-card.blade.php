@props([
    'src',
    'title',
    'viewerTitle' => null,
    'description' => 'Visualizzazione documento PDF',
    'actionUrl' => null,
    'actionLabel' => null,
    'actionTarget' => null,
])

<x-ui.card>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                {{ $title }}
            </h4>

            <p class="text-muted mb-0">
                {{ $description }}
            </p>
        </div>

        @if ($actionUrl && $actionLabel)
            <a href="{{ $actionUrl }}" @if ($actionTarget) target="{{ $actionTarget }}" @endif
                @if ($actionTarget === '_blank') rel="noopener" @endif
                class="btn btn-outline-primary rounded-pill px-4">
                {{ $actionLabel }}
            </a>
        @endif
    </div>

    <x-ui.pdf-viewer :src="$src" :title="$viewerTitle ?? $title" />
</x-ui.card>
