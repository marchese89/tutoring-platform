@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Esercizio'" />
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
                        Esercizio: {{ $exercise->title }}
                    </p>
                </div>

                <div class="mb-4">
                    <x-ui.document-upload-card title="Traccia" :preview-url="route('protected-files.show', [
                        'path' => $exercise->prompt_file,
                    ])" :upload-url="route('admin.exercises.trace.update', $exercise->id)" input-name="prompt_file"
                        progress-label="Caricamento traccia" />
                </div>

                <div class="mb-4">
                    <x-ui.document-upload-card title="Svolgimento" :preview-url="route('protected-files.show', [
                        'path' => $exercise->solution_file,
                    ])" :upload-url="route('admin.exercises.execution.update', $exercise->id)"
                        input-name="solution_file" progress-label="Caricamento svolgimento" />
                </div>

                <x-ui.form-card title="Dettagli esercizio" description="Modifica titolo e prezzo dell'esercizio."
                    icon="bi-journal-code">
                    <form method="POST" action="{{ route('admin.exercises.update', $exercise->id) }}">
                        @csrf
                        @method('PUT')

                        <x-ui.form-field name="title" label="Titolo" maxlength="255" :value="old('title', $exercise->title)" />

                        <x-ui.form-field name="price" label="Prezzo (€)" type="number" min="0" step="0.01"
                            :value="old('price', $exercise->price)" />

                        <x-ui.primary-button type="submit">
                            Salva modifiche
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
