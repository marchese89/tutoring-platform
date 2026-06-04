@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Aggiungi Certificato'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <x-ui.form-card
                    title="File certificato"
                    description="Carica il file del certificato prima di completare il salvataggio."
                    icon="bi-file-earmark-arrow-up">
                    <div class="mb-4">
                        <div class="ratio ratio-16x9 rounded-4 overflow-hidden border bg-light">
                            @if (session()->has('uploaded_cert'))
                                <iframe
                                    title="Anteprima certificato"
                                    src="{{ session('uploaded_cert') }}#view=FitH">
                                </iframe>
                            @else
                                <div class="d-flex align-items-center justify-content-center text-muted">
                                    Nessun file caricato.
                                </div>
                            @endif
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route('admin.account.certificates.uploads.store') }}"
                        enctype="multipart/form-data"
                        class="mb-4"
                        data-upload-progress-form>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="file">
                                Seleziona file
                            </label>

                            <input
                                type="file"
                                class="form-control rounded-3 @error('file') is-invalid @enderror"
                                id="file"
                                name="file"
                                required>
                            @error('file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <x-ui.upload-progress label="Caricamento certificato" />

                        <x-ui.primary-button type="submit">
                            Upload file
                        </x-ui.primary-button>
                    </form>

                    @if (session()->has('uploaded_cert'))
                        <form method="POST" action="{{ route('admin.account.certificates.uploads.destroy') }}">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                Elimina file
                            </button>
                        </form>
                    @endif
                </x-ui.form-card>

                @if (session()->has('uploaded_cert'))
                    <x-ui.form-card
                        class="mt-4"
                        title="Dettagli certificato"
                        description="Assegna un nome al certificato prima di salvarlo."
                        icon="bi-award">
                        <form method="POST" action="{{ route('admin.account.certificates.store') }}">
                            @csrf

                            <x-ui.form-field
                                name="name"
                                label="Nome certificato"
                                maxlength="255"
                                :value="old('name')" />

                            <x-ui.primary-button type="submit">
                                Aggiungi certificato
                            </x-ui.primary-button>
                        </form>
                    </x-ui.form-card>
                @endif
            </div>
        </div>
    </x-ui.page-section>
@endsection
