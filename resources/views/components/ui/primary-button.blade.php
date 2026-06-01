@props(['size' => 'md'])

@php
    $sizeClass = $size === 'sm' ? 'btn-sm px-3 py-1' : 'px-4 py-2';
@endphp

<a {{ $attributes->merge([
    'class' => 'btn btn-primary rounded-pill ' . $sizeClass,
]) }}>
    {{ $slot }}
</a>
