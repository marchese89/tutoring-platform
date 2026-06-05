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
            @if (Session::exists('uploaded_trace_ex')) src="/protected-files/{{ session()->get('uploaded_trace_ex') }}#view=FitH"
                @else
                    src="" @endif
            height="400px">
        </iframe>

        <form method="POST" action="{{ route('admin.exercises.trace.upload.store') }}" enctype="multipart/form-data" id="upload" data-upload-progress-form>
            @csrf
            @method('POST')
            <input type="hidden" name="id" value="{{ $id }}" />
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
        @if (Session::exists('uploaded_trace_ex'))
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
        @if (Session::exists('uploaded_trace_ex'))
            <h4>Svolgimento</h4>

            <iframe width="90%"
                @if (Session::exists('uploaded_ex')) src="/protected-files/{{ session()->get('uploaded_ex') }}#view=FitH"
                    @else
                        src="" @endif
                height="400px">
            </iframe>

            <form method="POST" action="{{ route('admin.exercises.execution.upload.store') }}" enctype="multipart/form-data" id="upload2" data-upload-progress-form>
                @csrf
                @method('POST')
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
            @if (Session::exists('uploaded_ex'))
                <div class="col-12">
                    <form action="{{ route('admin.exercises.execution.session.destroy') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Cancella File</button>
                    </form>
                </div>
            @endif
        @endif

        @if (Session::exists('uploaded_trace_ex') && Session::exists('uploaded_ex'))
            <form method="POST" action="{{ route('admin.exercises.store') }}">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $id }}" />
                <div class="col-md-12">
                    <h5>Titolo</h5>
                    <input type="text" class="form-control @error('titolo') is-invalid @enderror" id="titolo"
                        name="titolo" maxlength="255" value="{{ old('titolo') }}">
                    @error('titolo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control @error('prezzo') is-invalid @enderror" id="prezzo"
                        name="prezzo" maxlength="5" value="{{ old('prezzo') }}" style="display: inline">
                    @error('prezzo')
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
