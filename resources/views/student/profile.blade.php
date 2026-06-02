@extends('layouts.student-dashboard')

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

                <form class="row g-4" method="POST" action="{{ route('student.account.address.update') }}">
                    @csrf

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">
                            Indirizzo (via/piazza)
                        </label>

                        <input type="text" class="form-control rounded-3 @error('inputIndirizzo') is-invalid @enderror"
                            id="inputIndirizzo" name="inputIndirizzo" maxlength="255"
                            value="{{ old('inputIndirizzo', auth()->user()->student->street) }}">
                        @error('inputIndirizzo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            N. Civico
                        </label>

                        <input type="text"
                            class="form-control rounded-3 @error('inputNumeroCivico') is-invalid @enderror"
                            id="inputNumeroCivico" name="inputNumeroCivico" maxlength="6"
                            value="{{ old('inputNumeroCivico', auth()->user()->student->house_number) }}">
                        @error('inputNumeroCivico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            Città
                        </label>

                        <input type="text" class="form-control rounded-3 @error('inputCitta') is-invalid @enderror"
                            id="inputCitta" name="inputCitta" maxlength="255"
                            value="{{ old('inputCitta', auth()->user()->student->city) }}">
                        @error('inputCitta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-1">
                        <label class="form-label fw-semibold">
                            Prov.
                        </label>

                        <input type="text" class="form-control rounded-3 @error('inputProvincia') is-invalid @enderror"
                            id="inputProvincia" name="inputProvincia" maxlength="2"
                            value="{{ old('inputProvincia', auth()->user()->student->province) }}">
                        @error('inputProvincia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-1">
                        <label class="form-label fw-semibold">
                            CAP
                        </label>

                        <input type="text" class="form-control rounded-3 @error('inputCAP') is-invalid @enderror"
                            id="inputCAP" name="inputCAP" maxlength="5"
                            value="{{ old('inputCAP', auth()->user()->student->postal_code) }}">
                        @error('inputCAP')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
