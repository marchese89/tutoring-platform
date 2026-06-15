@props([
    'action',
    'values',
    'description',
])

<x-ui.form-card title="Indirizzo" :description="$description" icon="bi-geo-alt">
    <form method="POST" action="{{ $action }}" class="row g-4" data-address-form>
        @csrf

        <x-ui.form-field wrapper-class="col-md-5" name="street" label="Indirizzo (via/piazza)" maxlength="255"
            :value="old('street', $values['street'])" required />

        <x-ui.form-field wrapper-class="col-md-2" name="house_number" label="N. civico" maxlength="6"
            :value="old('house_number', $values['house_number'])" required />

        <x-ui.form-field wrapper-class="col-md-3" name="city" label="Città" maxlength="255"
            :value="old('city', $values['city'])" required />

        <x-ui.form-field wrapper-class="col-md-1" name="province" label="Prov." maxlength="2"
            :value="old('province', $values['province'])" required />

        <x-ui.form-field wrapper-class="col-md-1" name="postal_code" label="CAP" maxlength="5"
            :value="old('postal_code', $values['postal_code'])" inputmode="numeric" required />

        <div class="col-12 pt-2">
            <x-ui.primary-button type="submit">
                Salva modifiche
            </x-ui.primary-button>
        </div>
    </form>
</x-ui.form-card>
