@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Visualizza Richiesta Lezione'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center">
        <h3>Richiesta Lezione: </h3>
        <h3 style="color: blue">{{ $richiesta->title }}</h3>
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected-files/{{ $richiesta->trace }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        @if ($richiesta->execution != null)
            <h4>Soluzione</h4>
            <iframe width="90%" src="/protected-files/{{ $richiesta->execution }}#view=FitH" height="800px">
            </iframe>
            <br>
            <br>
            @if ($richiesta->escaped == 0)
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
            <form method="POST" action="{{ route('admin.lesson-requests.solution.store', $richiesta->id) }}" enctype="multipart/form-data" id="upload">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $richiesta->id }}" />
                <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" name="file" />
                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <p>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100" id="progressbar" style="display: none">
                    <div class="progress-bar" style="width: 25%" id="percent">25%</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary"
                        onclick="upload('upload','file','{{ route('admin.lesson-requests.solution.store', $richiesta->id) }}',1)">Upload</button>
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
