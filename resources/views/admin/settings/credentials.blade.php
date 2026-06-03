@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Credenziali'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <div class="col-lg-6">
                <x-ui.form-card
                    class="h-100"
                    title="Modifica Email"
                    description="Aggiorna l'indirizzo email usato per accedere."
                    icon="bi-envelope">
                    <form method="POST" action="{{ route('admin.account.email.update') }}">
                        @csrf

                        <x-ui.form-field
                            type="email"
                            name="inputEmail"
                            label="Nuova Email"
                            maxlength="255"
                            :value="old('inputEmail', auth()->user()->email)" />

                        <x-ui.primary-button type="submit">
                            Aggiorna Email
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>

            <div class="col-lg-6">
                <x-ui.form-card
                    class="h-100"
                    title="Modifica Password"
                    description="Aggiorna la password dell'account amministratore."
                    icon="bi-lock">
                    @if (session()->has('success'))
                        <div class="alert alert-success rounded-3">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.account.password.update') }}">
                        @csrf

                        <x-ui.form-field
                            wrapper-class="mb-2"
                            type="password"
                            name="inputPassword_old"
                            label="Vecchia Password"
                            error-name="pass0" />

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword_old()" id="showOld">
                            <label class="form-check-label" for="showOld">
                                Mostra Password
                            </label>
                        </div>

                        <x-ui.form-field
                            wrapper-class="mb-2"
                            type="password"
                            name="inputPassword"
                            label="Nuova Password" />

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword1()" id="showNew">
                            <label class="form-check-label" for="showNew">
                                Mostra Password
                            </label>
                        </div>

                        <x-ui.form-field
                            wrapper-class="mb-2"
                            type="password"
                            id="inputPassword2"
                            name="inputPassword_confirmation"
                            label="Conferma Password" />

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword2()" id="showConfirm">
                            <label class="form-check-label" for="showConfirm">
                                Mostra Password
                            </label>
                        </div>

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
