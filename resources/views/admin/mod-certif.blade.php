@extends('admin.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Modifica Certificati'" />
@endsection

@section('inner')
    <div class="container py-4" style="max-width: 1000px;">

        <div class="text-center mb-4">
            <button class="btn btn-primary" onclick="location.href='aggiungi-certif'">
                Aggiungi certificato
            </button>
        </div>

        @php
            $certificates = DB::table('certificates')->select('*')->get();
        @endphp

        @foreach ($certificates as $item)
            <div class="card shadow-sm mb-4">

                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Certificato #{{ $item->id }}</strong>

                    <form method="POST" action="elimina_certificato">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <button type="submit" class="btn btn-sm btn-danger">
                            Elimina
                        </button>
                    </form>
                </div>

                <div class="card-body">

                    {{-- NOME CERTIFICATO --}}
                    <form method="POST" action="mod-nome-cert-admin" class="mb-4">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">

                        <label class="form-label">Nome certificato</label>

                        <input class="form-control" type="text" id="nome_{{ $item->id }}"
                            name="nome_{{ $item->id }}" maxlength="255" value="{{ $item->nome }}">

                        <script>
                            var nome_{{ $item->id }} =
                                new LiveValidation('nome_{{ $item->id }}', {
                                    onlyOnSubmit: true
                                });

                            nome_{{ $item->id }}.add(Validate.Presence);
                            nome_{{ $item->id }}.add(Validate.SoloTesto);
                        </script>

                        <button type="submit" class="btn btn-primary mt-2">
                            Modifica nome
                        </button>
                    </form>

                    {{-- FILE --}}
                    <div class="mb-3">
                        <label class="form-label">File certificato</label>

                        <iframe width="100%" height="350"
                            @if ($item->percorso_file) src="{{ $item->percorso_file }}#view=FitH" @endif>
                        </iframe>
                    </div>

                    {{-- UPLOAD --}}
                    <form method="POST" action="mod-foto-cert-admin" enctype="multipart/form-data"
                        id="upload_{{ $item->id }}">

                        @csrf

                        <input type="hidden" name="id" value="{{ $item->id }}">

                        <div class="mb-2">
                            <input type="file" class="form-control" id="file_{{ $item->id }}"
                                name="file_{{ $item->id }}">
                        </div>

                        <div class="progress mb-2" id="progressbar_{{ $item->id }}"
                            style="display:none; height: 18px;">
                            <div class="progress-bar" id="percent_{{ $item->id }}" style="width: 0%;">
                                0%
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary"
                            onclick="upload('upload_{{ $item->id }}','file_{{ $item->id }}','mod-foto-cert-admin',1)">
                            Upload file
                        </button>
                    </form>

                </div>
            </div>
        @endforeach

    </div>
@endsection
