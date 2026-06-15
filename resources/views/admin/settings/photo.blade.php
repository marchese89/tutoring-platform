@extends('layouts.admin-dashboard')

@push('styles')
    <style>
        .admin-photo-preview {
            width: min(100%, 320px);
            aspect-ratio: 4 / 5;
            object-fit: cover;
            object-position: center;
            border-radius: 8px;
        }
    </style>
@endpush

@section('page-title')
    <x-ui.section-header title="Modifica foto" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.form-card
                    title="Foto profilo amministratore"
                    description="Carica o sostituisci l'immagine mostrata nel sito."
                    icon="bi-person-circle">
                    @if ($photoPath)
                        <div class="text-center mb-4">
                            <img alt="Foto profilo amministratore" src="{{ $photoPath }}"
                                class="admin-photo-preview shadow-sm border">
                        </div>
                    @else
                        <div class="mb-4">
                            <x-ui.empty-state title="Nessuna foto caricata"
                                text="Seleziona un'immagine per visualizzarla nel sito." />
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('admin.account.photo.update') }}"
                        enctype="multipart/form-data"
                        id="upload"
                        data-upload-progress-form>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="file">
                                Seleziona immagine
                            </label>
                            <input type="file" class="form-control rounded-3 @error('file') is-invalid @enderror"
                                id="file" name="file" accept="image/jpeg,image/png,image/webp" required>

                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <x-ui.upload-progress label="Caricamento foto" />

                        <x-ui.primary-button
                            type="submit"
                            class="w-100 justify-content-center">
                            Carica foto
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
