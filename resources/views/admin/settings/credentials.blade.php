@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Credenziali'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <div class="col-lg-6">
                <x-ui.form-card class="h-100" title="Modifica Email"
                    description="Aggiorna l'indirizzo email usato per accedere." icon="bi-envelope">
                    <form method="POST" action="{{ route('admin.account.email.update') }}">
                        @csrf

                        <x-ui.form-field type="email" name="email" label="Nuova Email" maxlength="255"
                            :value="old('email', auth()->user()->email)" />

                        <x-ui.primary-button type="submit">
                            Aggiorna Email
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>

            <div class="col-lg-6">
                <x-ui.form-card class="h-100" title="Modifica Password"
                    description="Aggiorna la password dell'account amministratore." icon="bi-lock">
                    @if (session()->has('success'))
                        <div class="alert alert-success rounded-3">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.account.password.update') }}">
                        @csrf

                        <x-ui.password-field name="current_password" label="Vecchia Password"
                            autocomplete="current-password" maxlength="255" />


                        <x-ui.password-field name="password" label="Nuova Password" autocomplete="new-password"
                            maxlength="255" />

                        <x-ui.password-field name="password_confirmation" label="Conferma Password"
                            autocomplete="new-password" maxlength="255" />

                        <div class="alert alert-light border rounded-4 small mb-4">
                            Password: almeno 10 caratteri, una maiuscola, una minuscola, un numero e un carattere speciale
                            (@ # ! ? . , ; :). Evitare piu di 2 caratteri uguali consecutivi.
                        </div>

                        <x-ui.primary-button type="submit">
                            Aggiorna Password
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
