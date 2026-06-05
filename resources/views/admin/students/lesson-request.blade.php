@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Visualizza Richiesta Lezione'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center">
        <h3>Richiesta Lezione: </h3>
        <h3 style="color: blue">{{ $lessonRequest->title }}</h3>
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected-files/{{ $lessonRequest->request_file }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        @if ($lessonRequest->solution_file != null)
            <h4>Soluzione</h4>
            <iframe width="90%" src="/protected-files/{{ $lessonRequest->solution_file }}#view=FitH" height="800px">
            </iframe>
            <br>
            <br>
            @if ($lessonRequest->is_fulfilled == 0)
                <div class="col-12">
                    <form action="{{ route('admin.lesson-requests.solution.destroy', $lessonRequest->id) }}" method="POST">
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
            <form method="POST" action="{{ route('admin.lesson-requests.solution.store', $lessonRequest->id) }}" enctype="multipart/form-data" id="upload" data-upload-progress-form>
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $lessonRequest->id }}" />
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
            <form action="{{ route('admin.lesson-requests.price.store', $lessonRequest->id) }}" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $lessonRequest->id }}" />
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                        name="price" value="{{ old('price', $lessonRequest->price) }}" maxlength="5" style="display: inline">
                    @error('price')
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
