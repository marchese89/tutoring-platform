@extends('layouts.dashboard-admin')

@section('inner')
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="dashboard-admin">Dashboard</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="imp-account">Impostazioni Account</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="mod-dati-pers">Modifica Dati Personali</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="mod-certif">Modifica Certificati</a>
        </li>
    </ul>
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
            <form method="POST" action="crea-foto-cert-admin" enctype="multipart/form-data" id="upload">
                @csrf
                <input type="file" class="form-control" id="file" name="file" />
                <p>
                <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0"
                    aria-valuemax="100" id="progressbar" style="display: none">
                    <div class="progress-bar" style="width: 25%" id="percent">25%</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary"
                        onclick="upload('upload','file','mod-foto-cert-admin')">Upload</button>
                </div>
                <br>
                <br>

            </form>
            @if (Session::exists('uploaded_cert'))
                <div class="col-12">
                    <button class="btn btn-primary" onclick=location.href="del_cert_admin">Elimina File</button>
                </div>
            @endif

            <br>
            <br>
        </div>
        @if (Session::exists('uploaded_cert') && Session::get('uploaded_cert') != null)
            <form method="POST" action="add-cert-admin">
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
