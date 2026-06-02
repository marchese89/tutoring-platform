@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Crea Fattura Extra'" />
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

                        <form class="row g-4" method="POST" action="{{ route('admin.invoices.extra.store') }}">

                            @csrf

                            {{-- NOME --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Nome
                                </label>

                                <input type="text" class="form-control @error('inputNome') is-invalid @enderror"
                                    id="inputNome" name="inputNome" maxlength="255" value="{{ old('inputNome') }}">
                                @error('inputNome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- COGNOME --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Cognome
                                </label>

                                <input type="text" class="form-control @error('inputCognome') is-invalid @enderror"
                                    id="inputCognome" name="inputCognome" maxlength="255" value="{{ old('inputCognome') }}">
                                @error('inputCognome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- INDIRIZZO --}}
                            <div class="col-md-5">

                                <label class="form-label fw-semibold">
                                    Indirizzo
                                </label>

                                <input type="text" class="form-control @error('inputIndirizzo') is-invalid @enderror"
                                    id="inputIndirizzo" name="inputIndirizzo" maxlength="255"
                                    value="{{ old('inputIndirizzo') }}">
                                @error('inputIndirizzo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- CIVICO --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    N. Civico
                                </label>

                                <input type="text" class="form-control @error('inputNumeroCivico') is-invalid @enderror"
                                    id="inputNumeroCivico" name="inputNumeroCivico" maxlength="6"
                                    value="{{ old('inputNumeroCivico') }}">
                                @error('inputNumeroCivico')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- CITTA --}}
                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Città
                                </label>

                                <input type="text" class="form-control @error('inputCitta') is-invalid @enderror"
                                    id="inputCitta" name="inputCitta" maxlength="255" value="{{ old('inputCitta') }}">
                                @error('inputCitta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- PROVINCIA --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Provincia
                                </label>

                                <input type="text" class="form-control @error('inputProvincia') is-invalid @enderror"
                                    id="inputProvincia" name="inputProvincia" maxlength="2"
                                    value="{{ old('inputProvincia') }}">
                                @error('inputProvincia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- CAP --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    CAP
                                </label>

                                <input type="text" class="form-control @error('inputCAP') is-invalid @enderror"
                                    id="inputCAP" name="inputCAP" maxlength="5" value="{{ old('inputCAP') }}">
                                @error('inputCAP')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- CODICE FISCALE --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Codice Fiscale
                                </label>

                                <input type="text" class="form-control @error('inputCF') is-invalid @enderror"
                                    id="inputCF" name="inputCF" maxlength="16" value="{{ old('inputCF') }}">
                                @error('inputCF')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- DESCRIZIONE --}}
                            <div class="col-md-8">

                                <label class="form-label fw-semibold">
                                    Descrizione
                                </label>

                                <input type="text" class="form-control @error('descrizione') is-invalid @enderror"
                                    id="descrizione" name="descrizione" maxlength="255" value="{{ old('descrizione') }}">
                                @error('descrizione')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- PREZZO --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Prezzo
                                </label>

                                <input type="text" class="form-control @error('prezzo') is-invalid @enderror"
                                    id="prezzo" name="prezzo" maxlength="12" value="{{ old('prezzo') }}">
                                @error('prezzo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- QUANTITA --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Qta
                                </label>

                                <input type="text" class="form-control @error('qta') is-invalid @enderror" id="qta"
                                    name="qta" maxlength="5" value="{{ old('qta') }}">
                                @error('qta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- NOTE --}}
                            <div class="col-12">

                                <label class="form-label fw-semibold">
                                    Note
                                </label>

                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="4" maxlength="255"
                                    style="resize:none">{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

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
