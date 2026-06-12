@extends('layouts.theme-areas-layout')

@section('page-title')
    <div class="container py-5">
        <h3 class="fw-bold display-6">
            Traccia Esercizio
        </h3>
    </div>
@endsection

@section('inner')
    <div class="container py-5">
        <x-ui.document-preview badge="Traccia Esercizio" badge-class="bg-warning text-dark" :course-name="$course->name"
            description="Gestione e revisione dell'esercizio selezionato" title-label="Titolo Esercizio" :title="$exercise->title"
            section-title="Traccia" :file-path="$exercise->prompt_file" />

    </div>
@endsection
