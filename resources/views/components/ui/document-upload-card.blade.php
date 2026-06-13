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
    <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-body-tertiary mb-4">
        @if ($previewUrl)
            <iframe src="{{ $previewUrl }}#view=FitH" title="{{ $title }}"></iframe>
        @else
            <div class="d-flex align-items-center justify-content-center text-muted">
                Nessun file caricato.
            </div>
        @endif
    </div>

    <form method="POST" action="{{ $uploadUrl }}" enctype="multipart/form-data" class="mb-3"
        data-upload-progress-form>
        @csrf

        @foreach ($hiddenFields as $name => $value)
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endforeach

        <div class="mb-3">
            <label class="form-label fw-semibold" for="{{ $inputId ?: $inputName }}">
                Seleziona file
            </label>

            <input type="file" class="form-control rounded-3 @error($inputName) is-invalid @enderror"
                id="{{ $inputId ?: $inputName }}" name="{{ $inputName }}" accept="application/pdf" required>

            @error($inputName)
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

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
