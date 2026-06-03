@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Lezione'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center;">
        <h2>Modifica Lezione Corso di</h2>
        <h2>"{{ $corso->name }}"</h2>
        <h3>Titolo Lezione</h3>
        <h3>"{{ $lezione->title }}"</h3>
        <br>

        <br>
        <h4>Presentazione</h4>

        <iframe width="90%" src="/protected-files/{{ $lezione->presentation }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.lessons.presentation.update', $id_lezione) }}" enctype="multipart/form-data"
                id="upload" data-upload-progress-form>
                @csrf
                @method('POST')
                <input type="file" class="form-control @error('file-pres-lez') is-invalid @enderror" id="file-pres-lez"
                    name="file-pres-lez" required />
                @error('file-pres-lez')
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

        <iframe width="90%" src="/protected-files/{{ $lezione->lesson }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.lessons.file.update', $id_lezione) }}" enctype="multipart/form-data" id="upload2" data-upload-progress-form>
                @csrf
                @method('POST')
                <input type="file" class="form-control @error('file-lesson') is-invalid @enderror" id="file-lesson"
                    name="file-lesson" required />
                @error('file-lesson')
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
            <form method="POST" action="{{ route('admin.lessons.update', $id_lezione) }}" id="modifica-lezione">
                @csrf
                @method('PUT')
                <input type="hidden" name="id_lezione" value="{{ $id_lezione }}" />
                <input type="hidden" name="id_corso" value="{{ $id_corso }}" />
                <div class="col-md-12">
                    <h5>Numero</h5>
                    <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero"
                        name="numero" value="{{ old('numero', $lezione->number) }}" maxlength="5">
                    @error('numero')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <h5>Titolo</h5>
                    <input type="text" class="form-control @error('titolo') is-invalid @enderror" id="titolo"
                        name="titolo" value="{{ old('titolo', $lezione->title) }}" maxlength="255">
                    @error('titolo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control @error('prezzo') is-invalid @enderror" id="prezzo"
                        name="prezzo" value="{{ old('prezzo', $lezione->price) }}" maxlength="5" style="display: inline">
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
