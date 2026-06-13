@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Nuova Lezione'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                <h2 class="h4 fw-bold text-center mb-4">
                    Corso: {{ $course->name }}
                </h2>

                <div class="mb-4">
                    <x-ui.document-upload-card title="Presentazione" :preview-url="$presentationUrl" :upload-url="route('admin.lessons.upload-presentation.store')"
                        input-name="presentation_file" progress-label="Caricamento presentazione" :delete-url="$presentationUploaded ? route('admin.lessons.upload-presentation.destroy') : null"
                        :hidden-fields="['course_id' => $id]" />
                </div>

                @if ($presentationUploaded)
                    <div class="mb-4">
                        <x-ui.document-upload-card title="Svolgimento" :preview-url="$contentUrl" :upload-url="route('admin.lessons.upload-file.store')"
                            input-name="content_file" progress-label="Caricamento svolgimento" :delete-url="$contentUploaded ? route('admin.lessons.upload-file.destroy') : null"
                            :hidden-fields="['course_id' => $id]" />
                    </div>
                @endif

                @if ($presentationUploaded && $contentUploaded)
                    <x-ui.form-card title="Dettagli lezione"
                        description="Completa i dati per aggiungere la lezione al corso." icon="bi-journal-plus">
                        <form method="POST" action="{{ route('admin.lessons.store') }}">
                            @csrf

                            <input type="hidden" name="course_id" value="{{ $id }}">

                            <x-ui.form-field name="number" label="Numero" type="number" min="1"
                                :value="old('number')" />

                            <x-ui.form-field name="title" label="Titolo" maxlength="255" :value="old('title')" />

                            <x-ui.form-field name="price" label="Prezzo (€)" type="number" min="0" step="0.01"
                                :value="old('price')" />

                            <x-ui.primary-button type="submit">
                                Aggiungi lezione
                            </x-ui.primary-button>
                        </form>
                    </x-ui.form-card>
                @endif
            </div>
        </div>
    </x-ui.page-section>
@endsection
