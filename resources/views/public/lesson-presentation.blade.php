@extends('layouts.theme-areas-layout')

@section('page-title')
    <div class="container py-4">
        <h3 class="fw-bold">
            Anteprima Lezione
        </h3>

    </div>
@endsection

@section('inner')
    <div class="container py-4">

        <x-ui.document-preview badge="Anteprima Lezione" :course-name="$course->name"
            description="Visualizzazione introduttiva della lezione" title-label="Titolo Lezione" :title="$lesson->title"
            section-title="Presentazione" :file-path="$lesson->presentation_file" />
    </div>
@endsection
