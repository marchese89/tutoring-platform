@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Nuova Lezione'" />
@endsection

@section('inner')
    <div class="container text-center" style="width: 40%">

        {{-- Presentation --}}
        <h5 class="mt-4">Presentazione</h5>

        <iframe width="90%" height="400"
            src="{{ session()->has('uploaded_lesson_presentation') ? route('protected-files.show', ['path' => session('uploaded_lesson_presentation')]) . '#view=FitH' : '' }}">
        </iframe>

        <form method="POST" action="{{ route('admin.lessons.upload-presentation.store') }}" enctype="multipart/form-data" id="upload-pres" data-upload-progress-form>
            @csrf
            <input type="hidden" name="course_id" value="{{ $id }}">

            <input type="file" class="form-control" name="presentation_file" accept="application/pdf" required>

            <x-ui.upload-progress label="Caricamento presentazione" />

            <button type="submit" class="btn btn-primary mt-2">
                Upload
            </button>
        </form>

        <form method="POST" action="{{ route('admin.lessons.upload-presentation.destroy') }}" class="mt-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Cancella file</button>
        </form>

        {{-- Content --}}
        @if (session()->has('uploaded_lesson_presentation'))
            <h5 class="mt-5">Svolgimento</h5>

            <iframe width="90%" height="400"
                src="{{ session()->has('uploaded_lesson_content') ? route('protected-files.show', ['path' => session('uploaded_lesson_content')]) . '#view=FitH' : '' }}">
            </iframe>

            <form method="POST" action="{{ route('admin.lessons.upload-file.store') }}" enctype="multipart/form-data" id="upload-lesson" data-upload-progress-form>
                @csrf
                <input type="hidden" name="course_id" value="{{ $id }}">

                <input type="file" class="form-control" name="content_file" accept="application/pdf" required>

                <x-ui.upload-progress label="Caricamento svolgimento" />

                <button type="submit" class="btn btn-primary mt-2">
                    Upload
                </button>
            </form>

            <form method="POST" action="{{ route('admin.lessons.upload-file.destroy') }}" class="mt-2">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Cancella file</button>
            </form>
        @endif


        {{-- Lesson form --}}
        @if (session()->has('uploaded_lesson_presentation') && session()->has('uploaded_lesson_content'))
            <form method="POST" action="{{ route('admin.lessons.store') }}" class="mt-4">
                @csrf
                <input type="hidden" name="course_id" value="{{ $id }}">

                <input type="text" class="form-control mt-2" name="number" placeholder="Numero">
                <input type="text" class="form-control mt-2" name="title" placeholder="Titolo">
                <input type="text" class="form-control mt-2" name="price" placeholder="Prezzo &euro;">

                <button class="btn btn-primary mt-3">Carica</button>
            </form>
        @endif

    </div>
@endsection
