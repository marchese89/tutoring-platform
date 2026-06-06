@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Lezione'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center;">
        <h2>Modifica Lezione Corso di</h2>
        <h2>"{{ $course->name }}"</h2>
        <h3>Titolo Lezione</h3>
        <h3>"{{ $lesson->title }}"</h3>
        <br>

        <br>
        <h4>Presentazione</h4>

        <iframe width="90%" src="/protected-files/{{ $lesson->presentation_file }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.lessons.presentation.update', $lesson->id) }}" enctype="multipart/form-data"
                id="upload" data-upload-progress-form>
                @csrf
                @method('POST')
                <input type="file" class="form-control @error('presentation_file') is-invalid @enderror" id="presentation_file"
                    name="presentation_file" accept="application/pdf" required />
                @error('presentation_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <x-ui.upload-progress label="Caricamento presentazione" />

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

                <br>
                <br>
            </form>
        </div>
        <br>

        <br>
        <h4>Svolgimento</h4>

        <iframe width="90%" src="/protected-files/{{ $lesson->content_file }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.lessons.file.update', $lesson->id) }}" enctype="multipart/form-data" id="upload2" data-upload-progress-form>
                @csrf
                @method('POST')
                <input type="file" class="form-control @error('content_file') is-invalid @enderror" id="content_file"
                    name="content_file" accept="application/pdf" required />
                @error('content_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <x-ui.upload-progress label="Caricamento svolgimento" />

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

                <br>
                <br>
            </form>
        </div>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.lessons.update', $lesson->id) }}" id="edit-lesson">
                @csrf
                @method('PUT')
                <input type="hidden" name="lesson" value="{{ $lesson->id }}" />
                <input type="hidden" name="course" value="{{ $course->id }}" />
                <div class="col-md-12">
                    <h5>Numero</h5>
                    <input type="text" class="form-control @error('number') is-invalid @enderror" id="number"
                        name="number" value="{{ old('number', $lesson->number) }}" maxlength="5">
                    @error('number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <h5>Titolo</h5>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" value="{{ old('title', $lesson->title) }}" maxlength="255">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                        name="price" value="{{ old('price', $lesson->price) }}" maxlength="5" style="display: inline">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <br>
                <div class="col-12" style="text-align:center">
                    <button type="submit" class="btn btn-primary">Modifica</button>
                </div>
            </form>
        </div>
        <br>
        <br>
    </div>
@endsection
