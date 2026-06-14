@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Visualizza richiesta lezione" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card>
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                <div>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                        Richiesta lezione
                    </span>

                    <h3 class="fw-bold mb-0">
                        {{ $lessonRequest->title }}
                    </h3>
                </div>

                <div class="text-lg-end">
                    <span
                        class="badge {{ $lessonRequest->is_fulfilled ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill px-3 py-2 mb-2">
                        {{ $lessonRequest->is_fulfilled ? 'Completata' : 'Da completare' }}
                    </span>

                    @if ($lessonRequest->price !== null)
                        <h5 class="fw-semibold mb-0">
                            {{ number_format($lessonRequest->price, 2, ',', '.') }}&euro;
                        </h5>
                    @endif
                </div>
            </div>
        </x-ui.card>

        <div class="row g-4 mt-0">
            <div class="col-12">
                <x-ui.card>
                    <h4 class="fw-bold mb-3">Traccia</h4>

                    <x-ui.pdf-viewer :src="'/protected-files/' . $lessonRequest->request_file" title="Richiesta dello studente" />
                </x-ui.card>
            </div>

            @if ($lessonRequest->solution_file)
                <div class="col-12">
                    <x-ui.card>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                            <h4 class="fw-bold mb-0">Soluzione</h4>

                            @if (! $lessonRequest->is_fulfilled)
                                <form action="{{ route('admin.lesson-requests.solution.destroy', $lessonRequest->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger">
                                        Elimina soluzione
                                    </button>
                                </form>
                            @endif
                        </div>

                        <x-ui.pdf-viewer :src="'/protected-files/' . $lessonRequest->solution_file" title="Soluzione" />
                    </x-ui.card>
                </div>
            @endif

            <div class="col-lg-7">
                <x-ui.form-card title="Carica soluzione" description="Aggiungi o sostituisci il PDF con lo svolgimento della richiesta."
                    icon="bi-file-earmark-arrow-up">
                    <form method="POST"
                        action="{{ route('admin.lesson-requests.solution.store', $lessonRequest->id) }}"
                        enctype="multipart/form-data" data-upload-progress-form>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="solution-file">File PDF</label>
                            <input id="solution-file" type="file"
                                class="form-control rounded-3 @error('file') is-invalid @enderror" name="file"
                                accept="application/pdf" required>

                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-ui.upload-progress label="Caricamento soluzione" />

                        <x-ui.primary-button type="submit">
                            Carica soluzione
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>

            <div class="col-lg-5">
                <x-ui.form-card title="Imposta prezzo" description="Definisci il prezzo e rendi disponibile la richiesta allo studente."
                    icon="bi-currency-euro">
                    <form action="{{ route('admin.lesson-requests.price.store', $lessonRequest->id) }}" method="POST">
                        @csrf

                        <x-ui.form-field name="price" label="Prezzo (€)" type="number"
                            :value="old('price', $lessonRequest->price)" min="0" step="0.01" required />

                        <x-ui.primary-button type="submit">
                            Salva prezzo
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
