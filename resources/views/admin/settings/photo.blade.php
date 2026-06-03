@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Foto'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.form-card
                    title="Foto profilo amministratore"
                    description="Carica o sostituisci l'immagine mostrata nel sito."
                    icon="bi-person-circle">
                    <div class="text-center mb-4">
                        <img
                            alt="Nessuna foto caricata"
                            src="{{ auth()->user()->admin->photo }}"
                            class="img-fluid rounded-4 shadow-sm border"
                            style="max-width: 300px; height: auto;">
                    </div>

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
                            <input type="file" class="form-control rounded-3" id="file" name="file" required>
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
