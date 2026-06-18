@props(['src', 'title' => null, 'size' => 'default'])

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
    <iframe src="{{ $src }}#view=FitH" title="{{ $title ?? __('ui.pdf.document') }}">
        <p>
            {{ __('ui.pdf.unsupported') }}
            <a href="{{ $src }}" target="_blank" rel="noopener">
                {{ __('ui.pdf.open_document') }}
            </a>
        </p>
    </iframe>
</div>
