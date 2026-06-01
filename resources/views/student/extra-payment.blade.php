@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Pagamento Extra'" />
@endsection

@section('inner')
    <div class="container" style="width: 60%;text-align:center">
        @if (session()->has('error'))
            <div class="alert alert-danger">
                {{ session()->get('error') }}
            </div>
        @endif
        <form class="row g-3" method="POST" action="{{ route('checkout.payment.prepare') }}" onsubmit="modifica_pass()">
            @csrf
            <div class="col-md-8">
                <label class="form-label">Descrizione</label>
                <input type="text" class="form-control" id="descrizione" name="descrizione" maxlength="255">
                <script type="text/javascript">
                    var descrizione_ = new LiveValidation('descrizione', {
                        onlyOnSubmit: true
                    });
                    descrizione_.add(Validate.Presence);
                    descrizione_.add(Validate.SoloTesto);
                </script>
            </div>
            <div class="col-md-2">
                <label class="form-label">Prezzo Unitario</label>
                <input type="text" class="form-control" id="prezzo" name="prezzo" maxlength="12">
                <script type="text/javascript">
                    var prezzo_ = new LiveValidation('prezzo', {
                        onlyOnSubmit: true
                    });
                    prezzo_.add(Validate.Presence);
                    prezzo_.add(Validate.InteriPositivi);
                </script>
            </div>
            <div class="col-md-2">
                <label class="form-label">Qta</label>
                <input type="text" class="form-control" id="qta" name="qta" maxlength="5">
                <script type="text/javascript">
                    var qta_ = new LiveValidation('qta', {
                        onlyOnSubmit: true
                    });
                    qta_.add(Validate.Presence);
                    qta_.add(Validate.InteriPositivi);
                </script>
            </div>
            <div class="col-12" style="text-align:center">
                <button type="submit" class="btn btn-primary">Paga</button>
            </div>
        </form>
    @endsection
