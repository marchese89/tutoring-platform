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
                    name="street"
                    label="Indirizzo (via/piazza)"
                    maxlength="255"
                    :value="old('street', auth()->user()->student->street)" />

                <x-ui.form-field
                    wrapper-class="col-md-2"
                    name="house_number"
                    label="N. Civico"
                    maxlength="6"
                    :value="old('house_number', auth()->user()->student->house_number)" />

                <x-ui.form-field
                    wrapper-class="col-md-3"
                    name="city"
                    label="Città"
                    maxlength="255"
                    :value="old('city', auth()->user()->student->city)" />

                <x-ui.form-field
                    wrapper-class="col-md-1"
                    name="province"
                    label="Prov."
                    maxlength="2"
                    :value="old('province', auth()->user()->student->province)" />

                <x-ui.form-field
                    wrapper-class="col-md-1"
                    name="postal_code"
                    label="CAP"
                    maxlength="5"
                    :value="old('postal_code', auth()->user()->student->postal_code)" />

                <div class="col-12 pt-2">
                    <x-ui.primary-button type="submit">
                        Salva Modifiche
                    </x-ui.primary-button>
                </div>
            </form>
        </x-ui.form-card>
    </x-ui.page-section>
@endsection
