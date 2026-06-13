@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Nuovo Esercizio'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <h2 class="h4 fw-bold text-center mb-4">
                    Corso: {{ $course->name }}
                </h2>

                <div class="mb-4">
                    <x-ui.document-upload-card title="Traccia" :preview-url="$promptUrl" :upload-url="route('admin.exercises.trace.upload.store')" input-name="prompt_file"
                        progress-label="Caricamento traccia" :delete-url="$promptUploaded ? route('admin.exercises.trace.session.destroy') : null" :hidden-fields="['course_id' => $id]" />
                </div>

                @if ($promptUploaded)
                    <div class="mb-4">
                        <x-ui.document-upload-card title="Svolgimento" :preview-url="$solutionUrl" :upload-url="route('admin.exercises.execution.upload.store')"
                            input-name="solution_file" progress-label="Caricamento svolgimento" :delete-url="$solutionUploaded ? route('admin.exercises.execution.session.destroy') : null" />
                    </div>
                @endif

                @if ($promptUploaded && $solutionUploaded)
                    <x-ui.form-card title="Dettagli esercizio"
                        description="Completa i dati per aggiungere l'esercizio al corso." icon="bi-journal-code">
                        <form method="POST" action="{{ route('admin.exercises.store') }}">
                            @csrf

                            <input type="hidden" name="course_id" value="{{ $id }}">

                            <x-ui.form-field name="title" label="Titolo" maxlength="255" :value="old('title')" />

                            <x-ui.form-field name="price" label="Prezzo (€)" type="number" min="0" step="0.01"
                                :value="old('price')" />

                            <x-ui.primary-button type="submit">
                                Aggiungi esercizio
                            </x-ui.primary-button>
                        </form>
                    </x-ui.form-card>
                @endif
            </div>
        </div>
    </x-ui.page-section>
@endsection
