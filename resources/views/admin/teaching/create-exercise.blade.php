@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Nuovo Esercizio'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center;width:35%">
        <h2>Nuovo Esercizio Corso di</h2>
        <h2>"{{ $course->name }}"</h2>
        <br>

        <br>
        <h4>Traccia</h4>

        <iframe width="90%"
            @if (Session::exists('uploaded_exercise_prompt')) src="/protected-files/{{ session()->get('uploaded_exercise_prompt') }}#view=FitH"
                @else
                    src="" @endif
            height="400px">
        </iframe>

        <form method="POST" action="{{ route('admin.exercises.trace.upload.store') }}" enctype="multipart/form-data" id="upload" data-upload-progress-form>
            @csrf
            @method('POST')
            <input type="hidden" name="course_id" value="{{ $id }}" />
            <input type="file" class="form-control @error('prompt_file') is-invalid @enderror" id="prompt_file"
                name="prompt_file" required />
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
        @if (Session::exists('uploaded_exercise_prompt'))
            <div class="col-12">
                <form action="{{ route('admin.exercises.trace.session.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary">Cancella File</button>
                </form>
            </div>
        @endif
        <br>

        <br>
        @if (Session::exists('uploaded_exercise_prompt'))
            <h4>Svolgimento</h4>

            <iframe width="90%"
                @if (Session::exists('uploaded_exercise_solution')) src="/protected-files/{{ session()->get('uploaded_exercise_solution') }}#view=FitH"
                    @else
                        src="" @endif
                height="400px">
            </iframe>

            <form method="POST" action="{{ route('admin.exercises.execution.upload.store') }}" enctype="multipart/form-data" id="upload2" data-upload-progress-form>
                @csrf
                @method('POST')
                <input type="file" class="form-control @error('solution_file') is-invalid @enderror" id="solution_file"
                    name="solution_file" required />
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
            @if (Session::exists('uploaded_exercise_solution'))
                <div class="col-12">
                    <form action="{{ route('admin.exercises.execution.session.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Cancella File</button>
                    </form>
                </div>
            @endif
        @endif

        @if (Session::exists('uploaded_exercise_prompt') && Session::exists('uploaded_exercise_solution'))
            <form method="POST" action="{{ route('admin.exercises.store') }}">
                @csrf
                @method('POST')
                <input type="hidden" name="course_id" value="{{ $id }}" />
                <div class="col-md-12">
                    <h5>Titolo</h5>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                        name="title" maxlength="255" value="{{ old('title') }}">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                        name="price" maxlength="5" value="{{ old('price') }}" style="display: inline">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <br>
                <div class="col-12" style="text-align:center">
                    <button type="submit" class="btn btn-primary">Carica</button>
                </div>
            </form>
        @endif
        <br>
        <br>
    </div>
@endsection
