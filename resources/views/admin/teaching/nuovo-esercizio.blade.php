@extends('layouts.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Nuovo Esercizio'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center;width:35%">
        @php
            use App\Models\Course;
            use App\Models\Lesson;

            $id = request('course');
            $corso = Course::where('id', '=', $id)->first();

        @endphp
        <h2>Nuovo Esercizio Corso di</h2>
        <h2>"{{ $corso->name }}"</h2>
        <br>

        <br>
        <h4>Traccia</h4>

        <iframe width="90%"
            @if (Session::exists('uploaded_trace_ex')) src="/protected_file/{{ session()->get('uploaded_trace_ex') }}#view=FitH"
                @else
                    src="" @endif
            height="400px">
        </iframe>

        <form method="POST" action="/exercises/trace/upload" enctype="multipart/form-data" id="upload">
            @csrf
            @method('POST')
            <input type="hidden" name="id" value="{{ $id }}" />
            <input type="file" class="form-control" id="file-trace-ex" name="file-trace-ex" />
            <p>
            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                aria-valuemax="100" id="progressbar" style="display: none">
                <div class="progress-bar" style="width: 25%" id="percent">25%</div>
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary"
                    onclick="upload('upload','file-trace-ex','exercises/trace/upload',1)">Upload</button>
            </div>

            <br>
            <br>
        </form>
        @if (Session::exists('uploaded_trace_ex'))
            <div class="col-12">
                <form action="/exercises/trace/session" method="POST">
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
                @if (Session::exists('uploaded_ex')) src="/protected_file/{{ session()->get('uploaded_ex') }}#view=FitH"
                    @else
                        src="" @endif
                height="400px">
            </iframe>

            <form method="POST" action="/exercises/execution/upload" enctype="multipart/form-data" id="upload2">
                @csrf
                @method('POST')
                <input type="file" class="form-control" id="file-ex" name="file-ex" />
                <p>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100" id="progressbar2" style="display: none">
                    <div class="progress-bar" style="width: 25%" id="percent">25%</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary"
                        onclick="upload('upload2','file-ex','upload-ex',2)">Upload</button>
                </div>

                <br>
                <br>
            </form>
            @if (Session::exists('uploaded_ex'))
                <div class="col-12">
                    <form action="/exercises/execution/session" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Cancella File</button>
                    </form>
                </div>
            @endif
        @endif

        @if (Session::exists('uploaded_trace_ex') && Session::exists('uploaded_ex'))
            <form method="POST" action="/exercises">
                @csrf
                @method('POST')
                <input type="hidden" name="id" value="{{ $id }}" />
                <div class="col-md-12">
                    <h5>Titolo</h5>
                    <input type="text" class="form-control" id="titolo" name="titolo" maxlength="255">
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
                    <input type="text" class="form-control" id="prezzo" name="prezzo" maxlength="5"
                        style="display: inline">
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
                    <button type="submit" class="btn btn-primary">Carica</button>
                </div>
            </form>
        @endif
        <br>
        <br>
    </div>
@endsection
