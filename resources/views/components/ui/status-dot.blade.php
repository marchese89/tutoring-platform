@props([
    'variant' => 'secondary',
    'label' => null,
])

<span
    {{ $attributes->merge([
        'class' => 'd-inline-block rounded-circle bg-' . $variant,
        'title' => $label,
        'aria-label' => $label,
        'role' => $label ? 'img' : null,
    ]) }}
    style="width: 14px; height: 14px;"
></span>
