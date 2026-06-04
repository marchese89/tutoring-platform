@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Esercizio'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center;width:100%">
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected-files/{{ $esercizio->prompt_file }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.exercises.trace.update', $exercise) }}" enctype="multipart/form-data" id="upload" data-upload-progress-form>
                @csrf
                <input type="hidden" name="id" value="{{ $exercise }}" />
                <input type="hidden" name="course" value="{{ $course }}" />
                <input type="file" class="form-control @error('file-trace-ex') is-invalid @enderror" id="file-trace-ex"
                    name="file-trace-ex" required />
                @error('file-trace-ex')
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

        <iframe width="90%" src="/protected-files/{{ $esercizio->solution_file }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.exercises.execution.update', $exercise) }}" enctype="multipart/form-data" id="upload2" data-upload-progress-form>
                @csrf
                <input type="hidden" name="id" value="{{ $exercise }}" />
                <input type="hidden" name="course" value="{{ $course }}" />
                <input type="file" class="form-control @error('file-ex') is-invalid @enderror" id="file-ex"
                    name="file-ex" required />
                @error('file-ex')
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
            <form method="POST" action="{{ route('admin.exercises.update', $exercise) }}" id="delete">
                @csrf
                @method('PUT')
                <div class="col-md-12">
                    <h5>Titolo</h5>
                    <input type="text" class="form-control @error('titolo') is-invalid @enderror" id="titolo"
                        name="titolo" value="{{ old('titolo', $esercizio->title) }}" maxlength="255">
                    @error('titolo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control @error('prezzo') is-invalid @enderror" id="prezzo"
                        name="prezzo" value="{{ old('prezzo', $esercizio->price) }}" maxlength="5" style="display: inline">
                    @error('prezzo')
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
