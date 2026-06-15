@props(['src', 'title' => 'Documento PDF', 'size' => 'default'])

@php
    $sizeClass = $size === 'compact' ? 'pdf-viewer--compact' : '';
@endphp

@once
    @push('styles')
        <style>
            .pdf-viewer {
                min-height: 520px;
                height: 75vh;
                max-height: 850px;
                overflow: hidden;
                border: 1px solid var(--bs-border-color);
                border-radius: 8px;
                background: var(--bs-tertiary-bg);
            }

            .pdf-viewer--compact {
                min-height: 320px;
                height: 45vh;
                max-height: 480px;
            }

            .pdf-viewer iframe {
                width: 100%;
                height: 100%;
                border: 0;
            }

            @media (max-width: 575.98px) {
                .pdf-viewer {
                    min-height: 440px;
                    height: 65vh;
                }

                .pdf-viewer--compact {
                    min-height: 300px;
                    height: 50vh;
                }
            }
        </style>
    @endpush
@endonce

<div {{ $attributes->class(['pdf-viewer', $sizeClass]) }}>
    <iframe src="{{ $src }}#view=FitH" title="{{ $title }}">
        <p>
            Il browser non supporta la visualizzazione PDF.
            <a href="{{ $src }}" target="_blank" rel="noopener">
                Apri il documento
            </a>
        </p>
    </iframe>
</div>
