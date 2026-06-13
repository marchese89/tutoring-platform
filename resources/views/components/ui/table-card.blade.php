@props(['title' => null])

<x-ui.card class="h-auto" body-class="p-4">
    @if ($title)
        <h4 class="fw-bold mb-4">
            {{ $title }}
        </h4>
    @endif

    <div class="table-responsive">
        {{ $slot }}
    </div>
</x-ui.card>
