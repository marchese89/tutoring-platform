@props([
    'variant' => 'secondary',
    'label' => null,
])

@once
    @push('styles')
        <style>
            .status-dot {
                width: 14px;
                height: 14px;
            }
        </style>
    @endpush
@endonce

<span
    {{ $attributes->merge([
        'class' => 'status-dot d-inline-block rounded-circle bg-' . $variant,
        'title' => $label,
        'aria-label' => $label,
        'role' => $label ? 'img' : null,
    ]) }}></span>
