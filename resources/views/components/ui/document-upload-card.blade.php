@props([
    'title',
    'previewUrl' => null,
    'uploadUrl',
    'inputName',
    'inputId' => null,
    'progressLabel',
    'deleteUrl' => null,
    'deleteLabel' => 'Elimina file',
    'hiddenFields' => [],
])

<x-ui.form-card :title="$title" icon="bi-file-earmark-pdf">
    <div class="mb-4">
        @if ($previewUrl)
            <x-ui.pdf-viewer :src="$previewUrl" :title="$title" size="compact" />
        @else
            <x-ui.empty-state title="Nessun file caricato"
                text="Seleziona un PDF per visualizzarne l'anteprima." />
        @endif
    </div>

    <form method="POST" action="{{ $uploadUrl }}" enctype="multipart/form-data" class="mb-3"
        data-upload-progress-form>
        @csrf

        @foreach ($hiddenFields as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach

        <x-ui.form-file :name="$inputName" :id="$inputId ?: $inputName" label="Seleziona file"
            accept="application/pdf" required />

        <x-ui.upload-progress :label="$progressLabel" />

        <x-ui.primary-button type="submit">
            Carica file
        </x-ui.primary-button>
    </form>

    @if ($deleteUrl)
        <form method="POST" action="{{ $deleteUrl }}">
            @csrf
            @method('DELETE')

            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                {{ $deleteLabel }}
            </button>
        </form>
    @endif
</x-ui.form-card>
