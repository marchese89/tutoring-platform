@props(['title' => null])

<x-ui.card class="h-auto" body-class="p-4">
    @if ($title || isset($actions))
        <div class="d-flex flex-column flex-sm-row justify-content-between
        align-items-sm-center gap-3 mb-4">
            @if ($title)
                <h4 class="fw-bold mb-0">
                    {{ $title }}
                </h4>
            @endif

            @isset($actions)
                <div>
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endif

    <div class="table-responsive">
        {{ $slot }}
    </div>
</x-ui.card>
