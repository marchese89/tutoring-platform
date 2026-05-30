@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Aggiungi Certificato'" />
@endsection

@section('inner')
    <div class="container" style="text-align: center;width:60%">
        <h2>Aggiungi Certificato</h2>

        <br>
        <br>
        <br>
        <iframe width="90%" @if (Session::exists('uploaded_cert')) src="{{ Session::get('uploaded_cert') }}#view=FitH" @endif
            height="400px">
        </iframe>
        <br>
        <div class="container" style="text-align: center;width:60%">
            <form method="POST" action="{{ route('admin.account.certificates.uploads.store') }}" enctype="multipart/form-data" id="upload">
                @csrf
                <input type="file" class="form-control" id="file" name="file" />
                <p>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100" id="progressbar" style="display: none">
                    <div class="progress-bar" style="width: 25%" id="percent">25%</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary"
                        onclick="upload('upload','file','{{ route('admin.account.certificates.uploads.store') }}')">Upload</button>
                </div>
                <br>
                <br>

            </form>
            @if (Session::exists('uploaded_cert'))
                <div class="col-12">
                    <form method="POST" action="{{ route('admin.account.certificates.uploads.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Elimina File</button>
                    </form>
                </div>
            @endif

            <br>
            <br>
        </div>
        @if (Session::exists('uploaded_cert') && Session::get('uploaded_cert') != null)
            <form method="POST" action="{{ route('admin.account.certificates.store') }}">
                @csrf
                <input type="hidden" name="id" />
                <input class="form-control col-4"=maxlength="255" type="text" name="nome" id="nome"></input>
                <script type="text/javascript">
                    var nome_ = new LiveValidation('nome', {
                        onlyOnSubmit: true
                    });
                    nome_.add(Validate.Presence);
                    nome_.add(Validate.SoloTesto);
                </script>
                <br>
                <button type="submit" class="btn btn-primary">Aggiungi Certificato</button>
            </form>
        @endif
        <br>
        <br>
    </div>
@endsection
