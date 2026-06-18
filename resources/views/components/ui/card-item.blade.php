@props([
    'title',
    'text',
    'url',
    'button' => 'Accedi',
    'icon' => null,
    'columnClass' => 'col-xl-4 col-md-6',
])

<div class="{{ $columnClass }}">
    <x-ui.card body-class="p-4 d-flex flex-column">
        @if ($icon)
            <div class="mb-4">
                <i class="{{ $icon }} fa-2x text-primary"></i>
            </div>
        @endif

        <h4 class="fw-bold mb-3">
            {{ $title }}
        </h4>

        <p class="text-muted mb-4 flex-grow-1">
            {{ $text }}
        </p>

        <div class="mt-auto">
            <x-ui.primary-button href="{{ $url }}">
                {{ $button }}
            </x-ui.primary-button>
        </div>
    </x-ui.card>

</div>
