{{-- resources/views/components/ui/section-header.blade.php --}}

@props(['title', 'description' => null])

<div class="my-3 text-center">
    <h2 class="fw-bold mb-3 bg-secondary bg-gradient text-white p-3 rounded shadow-sm">
        {{ $title }}
    </h2>

    @if ($description)
        <p class="text-muted mx-auto" style="max-width: 700px;">
            {{ $description }}
        </p>
    @endif
</div>
