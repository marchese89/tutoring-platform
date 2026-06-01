@props([
    'size' => 'md',
    'type' => 'button',
])

@php
    $sizeClass = $size === 'sm' ? 'btn-sm px-3 py-1' : 'px-4 py-2';
    $classes = 'btn btn-primary rounded-pill ' . $sizeClass;
@endphp

@if ($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
