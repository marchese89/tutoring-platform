@props(['title', 'description' => null])

<div class="my-3 text-center container">
    <h2 class="fw-bold mb-3 bg-secondary bg-gradient text-white p-3 rounded shadow-sm">
        {{ $title }}
    </h2>

    @if ($description)
        <p class="text-muted mb-0">
            {{ $description }}
        </p>
    @endif
</div>
