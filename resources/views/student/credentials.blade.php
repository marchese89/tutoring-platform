@extends('layouts.student-dashboard')

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
                    <form method="POST" action="{{ route('student.account.email.update') }}">
                        @csrf

                        <x-ui.form-field
                            type="email"
                            name="inputEmail"
                            label="Nuova Email"
                            maxlength="255"
                            :value="old('inputEmail', auth()->user()->email)" />

                        <x-ui.primary-button type="submit">
                            Salva Email
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>

            <div class="col-lg-6">
                <x-ui.form-card
                    class="h-100"
                    title="Modifica Password"
                    description="Scegli una password robusta per proteggere l'account."
                    icon="bi-lock">
                    @if (session()->has('success'))
                        <div class="alert alert-success rounded-3">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.account.password.update') }}">
                        @csrf

                        <x-ui.form-field
                            wrapper-class="mb-2"
                            type="password"
                            name="inputPassword_old"
                            label="Vecchia Password"
                            maxlength="255" />

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
                            label="Nuova Password"
                            maxlength="255" />

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword1()" id="showNew">
                            <label class="form-check-label" for="showNew">
                                Mostra Password
                            </label>
                        </div>

                        <x-ui.form-field
                            wrapper-class="mb-2"
                            type="password"
                            name="inputPassword2"
                            label="Conferma Password"
                            maxlength="255" />

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword2()" id="showConfirm">
                            <label class="form-check-label" for="showConfirm">
                                Mostra Password
                            </label>
                        </div>

                        <div class="alert alert-light border rounded-4 small mb-4">
                            <div class="fw-semibold mb-2">
                                Requisiti Password
                            </div>

                            <ul class="mb-0 ps-3">
                                <li>Minimo 10 caratteri</li>
                                <li>Almeno una lettera maiuscola</li>
                                <li>Almeno una lettera minuscola</li>
                                <li>Almeno un numero</li>
                                <li>Almeno un carattere speciale: @ # ! ? . , ; :</li>
                                <li>Non piu di due lettere o cifre ripetute consecutivamente</li>
                            </ul>
                        </div>

                        <x-ui.primary-button type="submit">
                            Salva Password
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
