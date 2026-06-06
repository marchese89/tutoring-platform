@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Esercizio'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center;width:100%">
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected-files/{{ $exercise->prompt_file }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.exercises.trace.update', $exercise->id) }}" enctype="multipart/form-data" id="upload" data-upload-progress-form>
                @csrf
                <input type="hidden" name="id" value="{{ $exercise->id }}" />
                <input type="hidden" name="course" value="{{ $course->id }}" />
                <input type="file" class="form-control @error('prompt_file') is-invalid @enderror" id="prompt_file"
                    name="prompt_file" accept="application/pdf" required />
                @error('prompt_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <x-ui.upload-progress label="Caricamento traccia" />

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

        <iframe width="90%" src="/protected-files/{{ $exercise->solution_file }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.exercises.execution.update', $exercise->id) }}" enctype="multipart/form-data" id="upload2" data-upload-progress-form>
                @csrf
                <input type="hidden" name="id" value="{{ $exercise->id }}" />
                <input type="hidden" name="course" value="{{ $course->id }}" />
                <input type="file" class="form-control @error('solution_file') is-invalid @enderror" id="solution_file"
                    name="solution_file" accept="application/pdf" required />
                @error('solution_file')
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
            <form method="POST" action="{{ route('admin.exercises.update', $exercise->id) }}" id="delete">
                @csrf
                @method('PUT')
                <div class="col-md-12">
                    <h5>Titolo</h5>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" value="{{ old('title', $exercise->title) }}" maxlength="255">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                        name="price" value="{{ old('price', $exercise->price) }}" maxlength="5" style="display: inline">
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
