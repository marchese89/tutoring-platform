@props(['title', 'description' => null])

<div class="container my-4">
    <div class="border-bottom pb-3 text-center text-md-start">
        <h1 class="h2 fw-bold mb-1">
            {{ $title }}
        </h1>

        @if ($description)
            <p class="text-muted mb-0">
                {{ $description }}
            </p>
        @endif
    </div>
</div>
