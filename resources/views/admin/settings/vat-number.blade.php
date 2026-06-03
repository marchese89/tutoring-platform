@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Partita IVA'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <x-ui.form-card
                    title="Partita IVA"
                    description="Aggiorna il dato fiscale mostrato nei documenti."
                    icon="bi-receipt">
                    <form method="POST" action="{{ route('admin.account.vat-number.update') }}">
                        @csrf

                        <x-ui.form-field
                            name="piva"
                            label="Partita IVA"
                            minlength="11"
                            maxlength="11"
                            :value="old('piva', auth()->user()->admin->piva)" />

                        <x-ui.primary-button type="submit" class="w-100 justify-content-center">
                            Salva
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
