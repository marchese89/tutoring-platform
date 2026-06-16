@props([
    'badge',
    'badgeClass' => 'bg-primary',
    'courseName',
    'courseLabel' => null,
    'description',
    'titleLabel',
    'title',
    'sectionTitle',
    'filePath',
])

@once
    @push('styles')
        <style>
            .document-preview {
                max-width: 75rem;
            }

            .document-preview-label {
                letter-spacing: 1px;
            }
        </style>
    @endpush
@endonce

<div class="document-preview mx-auto">
    <div class="bg-white shadow rounded-4 p-4 p-lg-5 border">
        <div class="text-center mb-5">
            <span class="badge {{ $badgeClass }} px-3 py-2 mb-3">
                {{ $badge }}
            </span>

            <h3 class="mb-2">
                <strong>{{ $courseLabel ?? __('public.catalog.course_label') }}</strong> {{ $courseName }}
            </h3>

            <p class="text-muted mb-0">
                {{ $description }}
            </p>
        </div>

        <div class="bg-light rounded-4 p-4 mb-4 border">
            <h5 class="document-preview-label text-uppercase text-muted mb-2">
                {{ $titleLabel }}
            </h5>

            <h4 class="fw-semibold mb-0">
                {{ $title }}
            </h4>
        </div>

        <div class="mb-4">
            <h4 class="fw-bold mb-3">
                {{ $sectionTitle }}
            </h4>

            <div class="rounded-4 overflow-hidden shadow-sm border bg-dark">
                <iframe class="w-100 border-0" height="800" src="/protected-files/{{ $filePath }}#view=FitH"
                    title="{{ $sectionTitle }}: {{ $title }}">
                </iframe>
            </div>
        </div>
    </div>
</div>
