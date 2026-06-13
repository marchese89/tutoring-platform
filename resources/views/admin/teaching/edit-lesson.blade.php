@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Lezione'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <div class="text-center mb-4">
                    <h2 class="h4 fw-bold mb-2">
                        Corso: {{ $course->name }}
                    </h2>

                    <p class="text-muted mb-0">
                        Lezione: {{ $lesson->title }}
                    </p>
                </div>

                <div class="mb-4">
                    <x-ui.document-upload-card title="Presentazione" :preview-url="route('protected-files.show', [
                        'path' => $lesson->presentation_file,
                    ])" :upload-url="route('admin.lessons.presentation.update', $lesson->id)"
                        input-name="presentation_file" progress-label="Caricamento presentazione" />
                </div>

                <div class="mb-4">
                    <x-ui.document-upload-card title="Svolgimento" :preview-url="route('protected-files.show', [
                        'path' => $lesson->content_file,
                    ])" :upload-url="route('admin.lessons.file.update', $lesson->id)"
                        input-name="content_file" progress-label="Caricamento svolgimento" />
                </div>

                <x-ui.form-card title="Dettagli lezione" description="Modifica numero, titolo e prezzo della lezione."
                    icon="bi-journal-text">
                    <form method="POST" action="{{ route('admin.lessons.update', $lesson->id) }}">
                        @csrf
                        @method('PUT')

                        <x-ui.form-field name="number" label="Numero" type="number" min="1" maxlength="5"
                            :value="old('number', $lesson->number)" />

                        <x-ui.form-field name="title" label="Titolo" maxlength="255" :value="old('title', $lesson->title)" />

                        <x-ui.form-field name="price" label="Prezzo (€)" type="number" min="0" step="0.01"
                            :value="old('price', $lesson->price)" />

                        <x-ui.primary-button type="submit">
                            Salva modifiche
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
