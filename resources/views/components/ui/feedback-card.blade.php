@props([
    'title',
    'text' => null,
    'icon' => 'bi-check-circle-fill',
    'iconClass' => 'text-success',
    'actionUrl' => null,
    'actionLabel' => null,
])

<x-ui.card>
    <div class="text-center py-4">
        <i class="bi {{ $icon }} {{ $iconClass }} display-4" aria-hidden="true">
        </i>

        <h3 class="fw-bold mt-3 mb-2">
            {{ $title }}
        </h3>

        @if ($text)
            <p class="text-muted mb-4">
                {{ $text }}
            </p>
        @endif

        @if ($actionUrl && $actionLabel)
            <x-ui.primary-button href="{{ $actionUrl }}">
                {{ $actionLabel }}
            </x-ui.primary-button>
        @endif
    </div>
</x-ui.card>
