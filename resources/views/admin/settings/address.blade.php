@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Indirizzo'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.form-card
            title="Indirizzo"
            description="Aggiorna i dati di residenza associati al profilo amministratore."
            icon="bi-geo-alt">
            <form method="POST" action="{{ route('admin.account.address.update') }}" class="row g-4">
                @csrf

                <x-ui.form-field
                    wrapper-class="col-md-5"
                    name="inputIndirizzo"
                    label="Indirizzo (via/piazza)"
                    maxlength="255"
                    :value="old('inputIndirizzo', auth()->user()->admin->street)" />

                <x-ui.form-field
                    wrapper-class="col-md-2"
                    name="inputNumeroCivico"
                    label="N. Civico"
                    maxlength="6"
                    :value="old('inputNumeroCivico', auth()->user()->admin->house_number)" />

                <x-ui.form-field
                    wrapper-class="col-md-3"
                    name="inputCitta"
                    label="Città"
                    maxlength="255"
                    :value="old('inputCitta', auth()->user()->admin->city)" />

                <x-ui.form-field
                    wrapper-class="col-md-1"
                    name="inputProvincia"
                    label="Prov."
                    maxlength="2"
                    :value="old('inputProvincia', auth()->user()->admin->province)" />

                <x-ui.form-field
                    wrapper-class="col-md-1"
                    name="inputCAP"
                    label="CAP"
                    maxlength="5"
                    :value="old('inputCAP', auth()->user()->admin->postal_code)" />

                <div class="col-12 pt-2">
                    <x-ui.primary-button type="submit">
                        Salva Modifiche
                    </x-ui.primary-button>
                </div>
            </form>
        </x-ui.form-card>
    </x-ui.page-section>
@endsection
