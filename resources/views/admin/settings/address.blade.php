@extends('layouts.admin-dashboard')

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
                        <input type="text" class="form-control @error('inputIndirizzo') is-invalid @enderror"
                            id="inputIndirizzo" name="inputIndirizzo" maxlength="255"
                            value="{{ old('inputIndirizzo', auth()->user()->admin->street) }}">
                        @error('inputIndirizzo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CIVICO --}}
                    <div class="col-md-4">
                        <label class="form-label">N. civico</label>
                        <input type="text" class="form-control @error('inputNumeroCivico') is-invalid @enderror"
                            id="inputNumeroCivico" name="inputNumeroCivico" maxlength="6"
                            value="{{ old('inputNumeroCivico', auth()->user()->admin->house_number) }}">
                        @error('inputNumeroCivico')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CITTA' --}}
                    <div class="col-md-6">
                        <label class="form-label">Città</label>
                        <input type="text" class="form-control @error('inputCitta') is-invalid @enderror" id="inputCitta"
                            name="inputCitta" maxlength="255" value="{{ old('inputCitta', auth()->user()->admin->city) }}">
                        @error('inputCitta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PROVINCIA --}}
                    <div class="col-md-2">
                        <label class="form-label">Provincia</label>
                        <input type="text" class="form-control @error('inputProvincia') is-invalid @enderror"
                            id="inputProvincia" name="inputProvincia" maxlength="2"
                            value="{{ old('inputProvincia', auth()->user()->admin->province) }}">
                        @error('inputProvincia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CAP --}}
                    <div class="col-md-4">
                        <label class="form-label">CAP</label>
                        <input type="text" class="form-control @error('inputCAP') is-invalid @enderror" id="inputCAP"
                            name="inputCAP" maxlength="5" value="{{ old('inputCAP', auth()->user()->admin->postal_code) }}">
                        @error('inputCAP')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
