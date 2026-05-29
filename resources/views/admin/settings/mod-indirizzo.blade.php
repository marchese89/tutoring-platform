@extends('layouts.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Modifica Indirizzo'" />
@endsection

@section('inner')
    <div class="container py-4" style="max-width: 900px;">

        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">Modifica indirizzo</h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('admin.account.address.update') }}" class="row g-3">
                    @csrf

                    {{-- VIA --}}
                    <div class="col-md-8">
                        <label class="form-label">Via / Piazza</label>
                        <input type="text" class="form-control" id="inputIndirizzo" name="inputIndirizzo" maxlength="255"
                            value="{{ auth()->user()->admin->street }}">

                        <script>
                            var via_ = new LiveValidation('inputIndirizzo', {
                                onlyOnSubmit: true
                            });
                            via_.add(Validate.Presence);
                            via_.add(Validate.SoloTesto);
                        </script>
                    </div>

                    {{-- CIVICO --}}
                    <div class="col-md-4">
                        <label class="form-label">N. civico</label>
                        <input type="text" class="form-control" id="inputNumeroCivico" name="inputNumeroCivico"
                            maxlength="6" value="{{ auth()->user()->admin->house_number }}">

                        <script>
                            var civico_ = new LiveValidation('inputNumeroCivico', {
                                onlyOnSubmit: true
                            });
                            civico_.add(Validate.Presence);
                        </script>
                    </div>

                    {{-- CITTA' --}}
                    <div class="col-md-6">
                        <label class="form-label">Città</label>
                        <input type="text" class="form-control" id="inputCitta" name="inputCitta" maxlength="255"
                            value="{{ auth()->user()->admin->city }}">

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
                        <label class="form-label">Provincia</label>
                        <input type="text" class="form-control" id="inputProvincia" name="inputProvincia" maxlength="2"
                            value="{{ auth()->user()->admin->province }}">

                        <script>
                            var provincia_ = new LiveValidation('inputProvincia', {
                                onlyOnSubmit: true
                            });
                            provincia_.add(Validate.Presence);
                            provincia_.add(Validate.SoloTesto);
                        </script>
                    </div>

                    {{-- CAP --}}
                    <div class="col-md-4">
                        <label class="form-label">CAP</label>
                        <input type="text" class="form-control" id="inputCAP" name="inputCAP" maxlength="5"
                            value="{{ auth()->user()->admin->postal_code }}">

                        <script>
                            var cap_ = new LiveValidation('inputCAP', {
                                onlyOnSubmit: true
                            });
                            cap_.add(Validate.Presence);
                            cap_.add(Validate.InteriPositivi);
                        </script>
                    </div>

                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            Salva modifiche
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
