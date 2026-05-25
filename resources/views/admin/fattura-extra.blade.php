@extends('layouts.dashboard-admin')

@section('page-title')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2>Crea Fattura Extra</h2>
            </div>
        </div>
    </div>
@endsection

@section('inner')
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="card border-0 shadow-sm rounded-4">

                    <div class="card-body p-5">

                        <h4 class="fw-bold mb-4">
                            Dati Fattura
                        </h4>

                        <form class="row g-4" method="POST" action="crea_fattura_extra" onsubmit="modifica_pass()">

                            @csrf

                            {{-- NOME --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Nome
                                </label>

                                <input type="text" class="form-control" id="inputNome" name="inputNome" maxlength="255">

                                <script>
                                    var nome_ = new LiveValidation('inputNome', {
                                        onlyOnSubmit: true
                                    });

                                    nome_.add(Validate.Presence);
                                    nome_.add(Validate.SoloTesto);
                                </script>

                            </div>

                            {{-- COGNOME --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Cognome
                                </label>

                                <input type="text" class="form-control" id="inputCognome" name="inputCognome"
                                    maxlength="255">

                                <script>
                                    var cognome_ = new LiveValidation('inputCognome', {
                                        onlyOnSubmit: true
                                    });

                                    cognome_.add(Validate.Presence);
                                    cognome_.add(Validate.SoloTesto);
                                </script>

                            </div>

                            {{-- INDIRIZZO --}}
                            <div class="col-md-5">

                                <label class="form-label fw-semibold">
                                    Indirizzo
                                </label>

                                <input type="text" class="form-control" id="inputIndirizzo" name="inputIndirizzo"
                                    maxlength="255">

                                <script>
                                    var via_ = new LiveValidation('inputIndirizzo', {
                                        onlyOnSubmit: true
                                    });

                                    via_.add(Validate.Presence);
                                    via_.add(Validate.SoloTesto);
                                </script>

                            </div>

                            {{-- CIVICO --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    N. Civico
                                </label>

                                <input type="text" class="form-control" id="inputNumeroCivico" name="inputNumeroCivico"
                                    maxlength="6">

                                <script>
                                    var n_civico_ = new LiveValidation('inputNumeroCivico', {
                                        onlyOnSubmit: true
                                    });

                                    n_civico_.add(Validate.Presence);
                                </script>

                            </div>

                            {{-- CITTA --}}
                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Città
                                </label>

                                <input type="text" class="form-control" id="inputCitta" name="inputCitta"
                                    maxlength="255">

                                <script>
                                    var citta_ = new LiveValidation('inputCitta', {
                                        onlyOnSubmit: true
                                    });

                                    citta_.add(Validate.Presence);
                                    citta_.add(Validate.SoloTesto);
                                </script>

                            </div>

                            {{-- PROVINCIA --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Provincia
                                </label>

                                <input type="text" class="form-control" id="inputProvincia" name="inputProvincia"
                                    maxlength="2">

                                <script>
                                    var provincia_ = new LiveValidation('inputProvincia', {
                                        onlyOnSubmit: true
                                    });

                                    provincia_.add(Validate.Presence);
                                    provincia_.add(Validate.SoloTesto);
                                </script>

                            </div>

                            {{-- CAP --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    CAP
                                </label>

                                <input type="text" class="form-control" id="inputCAP" name="inputCAP" maxlength="5">

                                <script>
                                    var cap_ = new LiveValidation('inputCAP', {
                                        onlyOnSubmit: true
                                    });

                                    cap_.add(Validate.Presence);
                                    cap_.add(Validate.InteriPositivi);
                                </script>

                            </div>

                            {{-- CODICE FISCALE --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Codice Fiscale
                                </label>

                                <input type="text" class="form-control" id="inputCF" name="inputCF" maxlength="16">

                                <script>
                                    var cf_ = new LiveValidation('inputCF', {
                                        onlyOnSubmit: true
                                    });

                                    cf_.add(Validate.Presence);
                                    cf_.add(Validate.CodiceFiscale);
                                </script>

                            </div>

                            {{-- DESCRIZIONE --}}
                            <div class="col-md-8">

                                <label class="form-label fw-semibold">
                                    Descrizione
                                </label>

                                <input type="text" class="form-control" id="descrizione" name="descrizione"
                                    maxlength="255">

                                <script>
                                    var descrizione_ = new LiveValidation('descrizione', {
                                        onlyOnSubmit: true
                                    });

                                    descrizione_.add(Validate.Presence);
                                    descrizione_.add(Validate.SoloTesto);
                                </script>

                            </div>

                            {{-- PREZZO --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Prezzo
                                </label>

                                <input type="text" class="form-control" id="prezzo" name="prezzo" maxlength="12">

                                <script>
                                    var prezzo_ = new LiveValidation('prezzo', {
                                        onlyOnSubmit: true
                                    });

                                    prezzo_.add(Validate.Presence);
                                    prezzo_.add(Validate.InteriPositivi);
                                </script>

                            </div>

                            {{-- QUANTITA --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Qta
                                </label>

                                <input type="text" class="form-control" id="qta" name="qta" maxlength="5">

                                <script>
                                    var qta_ = new LiveValidation('qta', {
                                        onlyOnSubmit: true
                                    });

                                    qta_.add(Validate.Presence);
                                    qta_.add(Validate.InteriPositivi);
                                </script>

                            </div>

                            {{-- NOTE --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Note
                                </label>

                                <textarea class="form-control" id="note" name="note" rows="4" maxlength="255" style="resize:none"></textarea>

                                <script>
                                    var note_ = new LiveValidation('note', {
                                        onlyOnSubmit: true
                                    });

                                    note_.add(Validate.Presence);
                                    note_.add(Validate.SoloTesto);
                                </script>

                            </div>

                            {{-- SUBMIT --}}
                            <div class="col-12 text-center mt-4">

                                <button type="submit" class="btn btn-primary rounded-pill px-5">

                                    Crea Fattura

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
