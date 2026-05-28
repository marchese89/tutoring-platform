@extends('layouts.dashboard-studente')

@section('page-title')
    <x-ui.section-header :title="'Modifica Dati Personali'" />
@endsection

@section('inner')
    <div class="container py-4">

        {{-- CARD DATI UTENTE --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">

                <div class="row">

                    <div class="col-md-6 mb-3 mb-md-0">
                        <label class="text-muted small mb-1">
                            Nome
                        </label>

                        <div class="fw-semibold fs-5">
                            {{ auth()->user()->name }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small mb-1">
                            Cognome
                        </label>

                        <div class="fw-semibold fs-5">
                            {{ auth()->user()->surname }}
                        </div>
                    </div>

                </div>

            </div>
        </div>

        {{-- FORM --}}
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">

                <form class="row g-4" method="POST" action="mod-indirizzo-stud">
                    @csrf

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">
                            Indirizzo (via/piazza)
                        </label>

                        <input type="text" class="form-control rounded-3" id="inputIndirizzo" name="inputIndirizzo"
                            maxlength="255" value="{{ auth()->user()->student->street }}">

                        <script type="text/javascript">
                            var via_ = new LiveValidation('inputIndirizzo', {
                                onlyOnSubmit: true
                            });

                            via_.add(Validate.Presence);
                            via_.add(Validate.SoloTesto);
                        </script>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            N. Civico
                        </label>

                        <input type="text" class="form-control rounded-3" id="inputNumeroCivico" name="inputNumeroCivico"
                            maxlength="6" value="{{ auth()->user()->student->house_number }}">

                        <script type="text/javascript">
                            var n_civico_ = new LiveValidation('inputNumeroCivico', {
                                onlyOnSubmit: true
                            });

                            n_civico_.add(Validate.Presence);
                        </script>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Città
                        </label>

                        <input type="text" class="form-control rounded-3" id="inputCitta" name="inputCitta"
                            maxlength="255" value="{{ auth()->user()->student->city }}">

                        <script type="text/javascript">
                            var citta_ = new LiveValidation('inputCitta', {
                                onlyOnSubmit: true
                            });

                            citta_.add(Validate.Presence);
                            citta_.add(Validate.SoloTesto);
                        </script>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label fw-semibold">
                            Prov.
                        </label>

                        <input type="text" class="form-control rounded-3" id="inputProvincia" name="inputProvincia"
                            maxlength="2" value="{{ auth()->user()->student->province }}">

                        <script type="text/javascript">
                            var provincia_ = new LiveValidation('inputProvincia', {
                                onlyOnSubmit: true
                            });

                            provincia_.add(Validate.Presence);
                            provincia_.add(Validate.SoloTesto);
                        </script>
                    </div>

                    <div class="col-md-1">
                        <label class="form-label fw-semibold">
                            CAP
                        </label>

                        <input type="text" class="form-control rounded-3" id="inputCAP" name="inputCAP" maxlength="5"
                            value="{{ auth()->user()->student->postal_code }}">

                        <script type="text/javascript">
                            var cap_ = new LiveValidation('inputCAP', {
                                onlyOnSubmit: true
                            });

                            cap_.add(Validate.Presence);
                            cap_.add(Validate.InteriPositivi);
                        </script>
                    </div>

                    <div class="col-12 pt-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            Salva Modifiche
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
