@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Crea fattura extra" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <x-ui.form-card title="Dati fattura"
                    description="Inserisci i dati del cliente e della prestazione da fatturare." icon="bi-receipt">
                    <form class="row g-4" method="POST" action="{{ route('admin.invoices.extra.store') }}">
                        @csrf

                        <x-ui.form-field name="first_name" label="Nome" wrapper-class="col-md-6"
                            :value="old('first_name')" maxlength="255" required />

                        <x-ui.form-field name="last_name" label="Cognome" wrapper-class="col-md-6"
                            :value="old('last_name')" maxlength="255" required />

                        <x-ui.form-field name="street" label="Indirizzo" wrapper-class="col-md-5"
                            :value="old('street')" maxlength="255" required />

                        <x-ui.form-field name="house_number" label="N. civico" wrapper-class="col-md-2"
                            :value="old('house_number')" maxlength="6" required />

                        <x-ui.form-field name="city" label="Città" wrapper-class="col-md-3" :value="old('city')"
                            maxlength="255" required />

                        <x-ui.form-field name="province" label="Provincia" wrapper-class="col-md-2"
                            :value="old('province')" maxlength="2" required />

                        <x-ui.form-field name="postal_code" label="CAP" wrapper-class="col-md-2"
                            :value="old('postal_code')" maxlength="5" inputmode="numeric" required />

                        <x-ui.form-field name="tax_code" label="Codice fiscale" wrapper-class="col-md-4"
                            :value="old('tax_code')" maxlength="16" required />

                        <x-ui.form-field name="description" label="Descrizione" wrapper-class="col-md-8"
                            :value="old('description')" maxlength="255" required />

                        <x-ui.form-field name="price" label="Prezzo" type="number" wrapper-class="col-md-2"
                            :value="old('price')" min="0" step="0.01" required />

                        <x-ui.form-field name="quantity" label="Quantità" type="number" wrapper-class="col-md-2"
                            :value="old('quantity')" min="1" step="1" required />

                        <x-ui.form-textarea name="note" label="Note" wrapper-class="col-12" :value="old('note')"
                            maxlength="255" />

                        <div class="col-12 text-center mt-4">
                            <x-ui.primary-button type="submit" class="px-5">
                                Crea fattura
                            </x-ui.primary-button>
                        </div>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
