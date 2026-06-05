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

                            {{-- First name --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Nome
                                </label>

                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    id="first_name" name="first_name" maxlength="255" value="{{ old('first_name') }}">
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Last name --}}
                            <div class="col-md-6">

                                <label class="form-label fw-semibold">
                                    Cognome
                                </label>

                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    id="last_name" name="last_name" maxlength="255" value="{{ old('last_name') }}">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Street --}}
                            <div class="col-md-5">

                                <label class="form-label fw-semibold">
                                    Indirizzo
                                </label>

                                <input type="text" class="form-control @error('street') is-invalid @enderror"
                                    id="street" name="street" maxlength="255"
                                    value="{{ old('street') }}">
                                @error('street')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- House number --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    N. Civico
                                </label>

                                <input type="text" class="form-control @error('house_number') is-invalid @enderror"
                                    id="house_number" name="house_number" maxlength="6"
                                    value="{{ old('house_number') }}">
                                @error('house_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- City --}}
                            <div class="col-md-3">

                                <label class="form-label fw-semibold">
                                    Città
                                </label>

                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                    id="city" name="city" maxlength="255" value="{{ old('city') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Province --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Provincia
                                </label>

                                <input type="text" class="form-control @error('province') is-invalid @enderror"
                                    id="province" name="province" maxlength="2"
                                    value="{{ old('province') }}">
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Postal code --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    CAP
                                </label>

                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                    id="postal_code" name="postal_code" maxlength="5" value="{{ old('postal_code') }}">
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Tax code --}}
                            <div class="col-md-4">

                                <label class="form-label fw-semibold">
                                    Codice Fiscale
                                </label>

                                <input type="text" class="form-control @error('tax_code') is-invalid @enderror"
                                    id="tax_code" name="tax_code" maxlength="16" value="{{ old('tax_code') }}">
                                @error('tax_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Description --}}
                            <div class="col-md-8">

                                <label class="form-label fw-semibold">
                                    Descrizione
                                </label>

                                <input type="text" class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" maxlength="255" value="{{ old('description') }}">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Price --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Prezzo
                                </label>

                                <input type="text" class="form-control @error('price') is-invalid @enderror"
                                    id="price" name="price" maxlength="12" value="{{ old('price') }}">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-2">

                                <label class="form-label fw-semibold">
                                    Qta
                                </label>

                                <input type="text" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                                    name="quantity" maxlength="5" value="{{ old('quantity') }}">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>

                            {{-- Notes --}}
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

                            {{-- Submit --}}
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
