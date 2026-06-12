@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Credenziali'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <div class="col-lg-6">
                <x-ui.form-card class="h-100" title="Modifica Email"
                    description="Aggiorna l'indirizzo email usato per accedere." icon="bi-envelope">
                    <form method="POST" action="{{ route('student.account.email.update') }}">
                        @csrf

                        <x-ui.form-field type="email" name="email" label="Nuova Email" maxlength="255"
                            :value="old('email', auth()->user()->email)" />

                        <x-ui.primary-button type="submit">
                            Salva Email
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>

            <div class="col-lg-6">
                <x-ui.form-card class="h-100" title="Modifica Password"
                    description="Scegli una password robusta per proteggere l'account." icon="bi-lock">
                    @if (session()->has('success'))
                        <div class="alert alert-success rounded-3">
                            {{ session()->get('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.account.password.update') }}">
                        @csrf

                        <x-ui.password-field name="current_password" label="Vecchia Password"
                            autocomplete="current-password" maxlength="255" />

                        <x-ui.password-field name="password" label="Nuova Password" autocomplete="new-password"
                            maxlength="255" />


                        <x-ui.password-field name="password_confirmation" label="Conferma Password"
                            autocomplete="new-password" maxlength="255" />

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
