@props(['class' => ''])

<div {{ $attributes->merge(['class' => trim('container py-4 ' . $class)]) }}>
    {{ $slot }}
</div>
