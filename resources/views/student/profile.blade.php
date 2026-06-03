@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Dati Personali'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card class="mb-4">
            <div class="row g-4">
                <div class="col-md-6">
                    <span class="text-muted small d-block mb-1">
                        Nome
                    </span>

                    <div class="fw-semibold fs-5">
                        {{ auth()->user()->name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <span class="text-muted small d-block mb-1">
                        Cognome
                    </span>

                    <div class="fw-semibold fs-5">
                        {{ auth()->user()->surname }}
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.form-card
            title="Indirizzo"
            description="Aggiorna i dati di residenza associati al tuo account."
            icon="bi-geo-alt">
            <form class="row g-4" method="POST" action="{{ route('student.account.address.update') }}">
                @csrf

                <x-ui.form-field
                    wrapper-class="col-md-5"
                    name="inputIndirizzo"
                    label="Indirizzo (via/piazza)"
                    maxlength="255"
                    :value="old('inputIndirizzo', auth()->user()->student->street)" />

                <x-ui.form-field
                    wrapper-class="col-md-2"
                    name="inputNumeroCivico"
                    label="N. Civico"
                    maxlength="6"
                    :value="old('inputNumeroCivico', auth()->user()->student->house_number)" />

                <x-ui.form-field
                    wrapper-class="col-md-3"
                    name="inputCitta"
                    label="Città"
                    maxlength="255"
                    :value="old('inputCitta', auth()->user()->student->city)" />

                <x-ui.form-field
                    wrapper-class="col-md-1"
                    name="inputProvincia"
                    label="Prov."
                    maxlength="2"
                    :value="old('inputProvincia', auth()->user()->student->province)" />

                <x-ui.form-field
                    wrapper-class="col-md-1"
                    name="inputCAP"
                    label="CAP"
                    maxlength="5"
                    :value="old('inputCAP', auth()->user()->student->postal_code)" />

                <div class="col-12 pt-2">
                    <x-ui.primary-button type="submit">
                        Salva Modifiche
                    </x-ui.primary-button>
                </div>
            </form>
        </x-ui.form-card>
    </x-ui.page-section>
@endsection
