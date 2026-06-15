@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Modifica dati personali" />
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
                        {{ $name }}
                    </div>
                </div>

                <div class="col-md-6">
                    <span class="text-muted small d-block mb-1">
                        Cognome
                    </span>

                    <div class="fw-semibold fs-5">
                        {{ $surname }}
                    </div>
                </div>
            </div>
        </x-ui.card>

        <x-ui.address-form :action="route('student.account.address.update')" :values="$address"
            description="Aggiorna i dati di residenza associati al tuo account." />
    </x-ui.page-section>
@endsection
