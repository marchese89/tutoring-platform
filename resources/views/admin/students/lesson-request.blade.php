@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Visualizza Richiesta Lezione'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center">
        <h3>Richiesta Lezione: </h3>
        <h3 style="color: blue">{{ $richiesta->title }}</h3>
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected-files/{{ $richiesta->request_file }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        @if ($richiesta->solution_file != null)
            <h4>Soluzione</h4>
            <iframe width="90%" src="/protected-files/{{ $richiesta->solution_file }}#view=FitH" height="800px">
            </iframe>
            <br>
            <br>
            @if ($richiesta->is_fulfilled == 0)
                <div class="col-12">
                    <form action="{{ route('admin.lesson-requests.solution.destroy', $richiesta->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-primary">
                            Elimina
                        </button>
                    </form>
                </div>
                <br>
            @endif
        @endif
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="{{ route('admin.lesson-requests.solution.store', $richiesta->id) }}" enctype="multipart/form-data" id="upload" data-upload-progress-form>
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $richiesta->id }}" />
                <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" required />
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <x-ui.upload-progress label="Caricamento soluzione" />

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>

                <br>
                <br>
            </form>
            <br>
            <br>
            <form action="{{ route('admin.lesson-requests.price.store', $richiesta->id) }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $richiesta->id }}" />
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control @error('prezzo') is-invalid @enderror" id="prezzo"
                        name="prezzo" value="{{ old('prezzo', $richiesta->price) }}" maxlength="5" style="display: inline">
                    @error('prezzo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <br>
                <div class="col-12" style="text-align:center">
                    <button type="submit" class="btn btn-primary">Carica Prezzo</button>
                </div>
            </form>
            <br>
            <br>
        </div>
    </div>
@endsection
