@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header title="Modifica indirizzo" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.address-form :action="route('admin.account.address.update')" :values="$address"
            description="Aggiorna i dati di residenza associati al profilo amministratore." />
    </x-ui.page-section>
@endsection
