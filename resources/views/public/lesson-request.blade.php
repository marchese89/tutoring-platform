@extends('layouts.layout-bootstrap')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    <div class="card-body p-4 p-lg-5">

                        <div class="text-center mb-5">
                            <h1 class="fw-bold mb-3">
                                Richiesta Lezione su Commissione
                            </h1>

                            <p class="text-muted fs-5 mb-2">
                                Carica una traccia, un esercizio o del materiale didattico.
                            </p>

                            <p class="text-muted fs-5 mb-2">
                                Riceverai una risposta personalizzata direttamente nel tuo profilo studente.
                            </p>

                            <p class="text-muted fs-5 mb-2">
                                Dopo la valutazione potrai visualizzare il prezzo e decidere se acquistare.
                            </p>

                            <p class="text-muted fs-5 mb-0">
                                Eventuali chiarimenti via chat sono inclusi dopo l'acquisto.
                            </p>
                        </div>

                        @if (!Auth::check())
                            <div class="alert alert-warning text-center rounded-4 border-0 shadow-sm py-4">

                                <h5 class="fw-semibold mb-3">
                                    Accesso richiesto
                                </h5>

                                <p class="mb-4">
                                    Devi effettuare il login come studente per utilizzare questa funzionalità.
                                </p>

                                <button class="btn btn-primary px-4 rounded-3"
                                    onclick="location.href='{{ route('login', ['back' => 1]) }}'">
                                    Login
                                </button>

                            </div>
                        @else
                            @if (!Session::exists('uploaded_lez_rich'))
                                <div class="mx-auto" style="max-width: 600px;">

                                    <div class="card bg-light border-0 rounded-4">
                                        <div class="card-body p-4">

                                            <form method="POST" action="{{ route('lesson-requests.files.store') }}"
                                                enctype="multipart/form-data" id="upload">

                                                @csrf

                                                <div class="mb-4">
                                                    <label class="form-label fw-semibold">
                                                        Seleziona il file
                                                    </label>

                                                    <input type="file"
                                                        class="form-control form-control-lg @error('file') is-invalid @enderror"
                                                        id="file" name="file" />
                                                    @error('file')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="progress mb-4" role="progressbar" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100" id="progressbar"
                                                    style="display: none; height: 24px;">

                                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                        style="width: 25%" id="percent">
                                                        25%
                                                    </div>
                                                </div>

                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary btn-lg rounded-3"
                                                        onclick="upload('upload','file','{{ route('lesson-requests.files.store') }}',1)">

                                                        Carica File

                                                    </button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>

                                </div>
                            @else
                                <div class="text-center">

                                    <div class="mb-4">
                                        <span class="badge bg-success px-3 py-2 rounded-pill fs-6">
                                            File caricato correttamente
                                        </span>
                                    </div>

                                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">

                                        <div class="card-body p-3">

                                            <iframe class="w-100 rounded-3 border" style="height: 600px;"
                                                src="/protected-files/{{ session('uploaded_lez_rich') }}#view=FitH">
                                            </iframe>

                                        </div>

                                    </div>

                                    <div class="mb-5">
                                        <form method="POST" action="{{ route('lesson-requests.files.destroy') }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger rounded-3 px-4">
                                                Elimina File
                                            </button>
                                        </form>
                                    </div>

                                    <div class="mx-auto" style="max-width: 700px;">

                                        <div class="card bg-light border-0 rounded-4">
                                            <div class="card-body p-4 p-lg-5">

                                                <h4 class="fw-semibold mb-4">
                                                    Invia la richiesta
                                                </h4>

                                                <form method="POST" action="{{ route('lesson-requests.store') }}">

                                                    @csrf

                                                    <div class="mb-4 text-start">

                                                        <label for="titolo" class="form-label fw-semibold">

                                                            Titolo / Descrizione

                                                        </label>

                                                        <input type="text"
                                                            class="form-control form-control-lg @error('titolo') is-invalid @enderror"
                                                            id="titolo" name="titolo" maxlength="255"
                                                            value="{{ old('titolo') }}"
                                                            placeholder="Inserisci una breve descrizione della richiesta">
                                                        @error('titolo')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror

                                                    </div>

                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-primary btn-lg rounded-3">

                                                            Invia Richiesta

                                                        </button>
                                                    </div>

                                                </form>

                                            </div>
                                        </div>

                                    </div>

                                </div>
                            @endif
                        @endif

                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
