@extends('layouts.dashboard-admin')

@section('page-title')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2>Visualizza Richiesta Lezione</h2>
            </div>
        </div>
    </div>
@endsection

@section('inner')
    <div class="container" style="text-align: center">
        <h3>Richiesta Lezione: </h3>
        <h3 style="color: blue">{{ $richiesta->title }}</h3>
        <h4>Traccia</h4>

        <iframe width="90%" src="/protected_file/{{ $richiesta->trace }}#view=FitH" height="800px">
        </iframe>
        <br>
        <br>
        @if ($richiesta->execution != null)
            <h4>Soluzione</h4>
            <iframe width="90%" src="/protected_file/{{ $richiesta->execution }}#view=FitH" height="800px">
            </iframe>
            <br>
            <br>
            @if ($richiesta->escaped == 0)
                <div class="col-12">
                    <form action="{{ route('lez-rich-rem-exec', $richiesta->id) }}" method="POST">
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
            <form method="POST" action="/sol-rich-upload" enctype="multipart/form-data" id="upload">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $richiesta->id }}" />
                <input type="file" class="form-control" id="file" name="file" />
                <p>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100" id="progressbar" style="display: none">
                    <div class="progress-bar" style="width: 25%" id="percent">25%</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary"
                        onclick="upload('upload','file','sol-rich-upload',1)">Upload</button>
                </div>

                <br>
                <br>
            </form>
            <br>
            <br>
            <form action="/carica-prezzo-lez-rich" method="POST">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $richiesta->id }}" />
                <div class="col-md-12">
                    <h5>Prezzo (&euro;)</h5>
                    <input type="text" class="form-control" id="prezzo" name="prezzo" value="{{ $richiesta->price }}"
                        maxlength="5" style="display: inline">
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
                    <button type="submit" class="btn btn-primary">Carica Prezzo</button>
                </div>
            </form>
            <br>
            <br>
        </div>
    </div>
@endsection
