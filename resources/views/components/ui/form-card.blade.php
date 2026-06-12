@props(['title', 'description' => null, 'icon' => null, 'class' => '', 'bodyClass' => 'p-4'])

<x-ui.card :class="$class" :body-class="$bodyClass">
    <div class="d-flex gap-3 align-items-start mb-4">
        @if ($icon)
            <div class="form-card-icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                <i class="bi {{ $icon }}"></i>
            </div>
        @endif

        <div>
            <h4 class="fw-bold mb-1">
                {{ $title }}
            </h4>

            @if ($description)
                <p class="text-muted mb-0">
                    {{ $description }}
                </p>
            @endif
        </div>
    </div>

    {{ $slot }}
</x-ui.card>

@once
    @push('styles')
        <style>
            .form-card-icon {
                background: #eef2ff;
                color: #4f46e5;
                height: 3rem;
                width: 3rem;
            }
        </style>
    @endpush
@endonce
