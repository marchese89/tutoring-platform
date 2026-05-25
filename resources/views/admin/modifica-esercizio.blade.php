@extends('layouts.dashboard-admin')

@section('inner')
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard-admin">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="insegnamento">Insegnamento</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="elenco-corsi">Elenco Corsi</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/modifica-dettagli-corso-{{ request('course') }}">Corso</a>
        </li>
    </ul>
    <div class="container" style="text-align: center;width:100%">
        @php
            use App\Models\Course;
            use App\Models\Exercise;

            $id_corso = request('course');
            $id_esercizio = request('exercise');
            $corso = Course::where('id', '=', $id_corso)->first();
            $esercizio = Exercise::where('id', '=', $id_esercizio)->first();

        @endphp
        <h2>Modifica Esercizio Corso di</h2>
        <h2>"{{ $corso->name }}"</h2>
        <h3>Titolo Esercizio</h3>
        <h3>"{{ $esercizio->title }}"</h3>
        <br>

        <br>
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected_file/{{ $esercizio->trace }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="trace-ex-re-upload" enctype="multipart/form-data" id="upload">
                @csrf
                <input type="hidden" name="id" value="{{ $id_esercizio }}" />
                <input type="hidden" name="id_corso" value="{{ $id_corso }}" />
                <input type="file" class="form-control" id="file-trace-ex" name="file-trace-ex" />
                <p>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100" id="progressbar" style="display: none">
                    <div class="progress-bar" style="width: 25%" id="percent">25%</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary"
                        onclick="upload('upload','file-trace-ex','trace-ex-re-upload',1)">Upload</button>
                </div>

                <br>
                <br>
            </form>
        </div>
        <br>

        <br>
        <h4>Svolgimento</h4>

        <iframe width="90%" src="/protected_file/{{ $esercizio->execution }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="ex-re-upload" enctype="multipart/form-data" id="upload2">
                @csrf
                <input type="hidden" name="id" value="{{ $id_esercizio }}" />
                <input type="hidden" name="id_corso" value="{{ $id_corso }}" />
                <input type="file" class="form-control" id="file-ex" name="file-ex" />
                <p>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100" id="progressbar2" style="display: none">
                    <div class="progress-bar" style="width: 25%" id="percent">25%</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary"
                        onclick="upload('upload2','file-ex','ex-re-upload',2)">Upload</button>
                </div>

                <br>
                <br>
            </form>
        </div>
        <div class="container" style="text-align: center;width:35%">
            <form method="POST" action="/exercises/{{ $id_esercizio }}" enctype="multipart/form-data" id="delete">
                @csrf
                @method('PUT')
                <div class="col-md-12">
                    <h5>Titolo</h5>
                    <input type="text" class="form-control" id="titolo" name="titolo"
                        value="{{ $esercizio->title }}" maxlength="255">
                    <script type="text/javascript">
                        var titolo_ = new LiveValidation('titolo', {
                            onlyOnSubmit: true
                        });
                        titolo_.add(Validate.Presence);
                        titolo_.add(Validate.SoloTesto);
                    </script>
                </div>
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control" id="prezzo" name="prezzo"
                        value="{{ $esercizio->price }}" maxlength="5" style="display: inline">
                    <script type="text/javascript">
                        var prezzo_ = new LiveValidation('prezzo', {
                            onlyOnSubmit: true
                        });
                        prezzo_.add(Validate.Presence);
                        prezzo_.add(Validate.InteriPositivi);
                    </script>
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
