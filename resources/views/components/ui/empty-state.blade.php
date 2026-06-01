@props(['title', 'text' => null])

<div {{ $attributes->merge(['class' => 'text-center py-5']) }}>
    <h4 class="text-muted mb-2">
        {{ $title }}
    </h4>

    @if ($text)
        <p class="text-muted mb-0">
            {{ $text }}
        </p>
    @endif
</div>
