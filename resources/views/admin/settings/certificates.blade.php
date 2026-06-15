@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Certificati'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="d-flex justify-content-end mb-4">
            <x-ui.primary-button href="{{ route('admin.account.certificates.create') }}">
                Aggiungi certificato
            </x-ui.primary-button>
        </div>

        <div class="row g-4">
            @forelse ($certificates as $certificate)
                <div class="col-12">
                    <x-ui.form-card
                        :title="'Certificato #' . $certificate->id"
                        description="Modifica nome e file associati al certificato."
                        icon="bi-award">
                        <div class="d-flex justify-content-end mb-4">
                            <form method="POST" action="{{ route('admin.account.certificates.destroy') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ $certificate->id }}">

                                <button type="submit" class="btn btn-outline-danger rounded-pill px-3">
                                    Elimina
                                </button>
                            </form>
                        </div>

                        <form method="POST" action="{{ route('admin.account.certificates.name.update') }}" class="mb-4">
                            @csrf
                            <input type="hidden" name="id" value="{{ $certificate->id }}">

                            <x-ui.form-field
                                name="name"
                                id="name_{{ $certificate->id }}"
                                label="Nome certificato"
                                maxlength="255"
                                :value="old('name', $certificate->name)" />

                            <x-ui.primary-button type="submit" size="sm">
                                Modifica nome
                            </x-ui.primary-button>
                        </form>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                File certificato
                            </label>

                            @if ($certificate->file_path)
                                <x-ui.pdf-viewer :src="$certificate->file_path" :title="'Certificato ' . $certificate->id"
                                    size="compact" />
                            @else
                                <x-ui.empty-state title="Nessun file caricato"
                                    text="Carica un PDF per visualizzare il certificato." />
                            @endif
                        </div>

                        <form
                            method="POST"
                            action="{{ route('admin.account.certificates.file.update') }}"
                            enctype="multipart/form-data"
                            data-upload-progress-form>
                            @csrf

                            <input type="hidden" name="id" value="{{ $certificate->id }}">

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="file_{{ $certificate->id }}">
                                    Sostituisci file
                                </label>

                                <input
                                    type="file"
                                    class="form-control rounded-3 @error('file') is-invalid @enderror"
                                    id="file_{{ $certificate->id }}"
                                    name="file"
                                    accept="application/pdf" required>
                                @error('file')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <x-ui.upload-progress label="Caricamento certificato" />

                            <x-ui.primary-button type="submit" size="sm">
                                Upload file
                            </x-ui.primary-button>
                        </form>
                    </x-ui.form-card>
                </div>
            @empty
                <div class="col-12">
                    <x-ui.empty-state
                        title="Nessun certificato presente"
                        text="Aggiungi un certificato per mostrarlo nel profilo pubblico." />
                </div>
            @endforelse
        </div>
    </x-ui.page-section>
@endsection
