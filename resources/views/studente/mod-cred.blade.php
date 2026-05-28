@extends('layouts.dashboard-studente')

@section('page-title')
    <x-ui.section-header :title="'Modifica Credenziali'" />
@endsection

@section('inner')
    <div class="container py-4">

        <div class="row g-4">

            {{-- EMAIL --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-envelope fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Modifica Email
                        </h4>

                        <form method="POST" action="mod-email-stud">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nuova Email
                                </label>

                                <input type="email" class="form-control rounded-3" id="inputEmail" name="inputEmail"
                                    maxlength="255" value="{{ auth()->user()->email }}">

                                <script type="text/javascript">
                                    var email1 = new LiveValidation('inputEmail', {
                                        onlyOnSubmit: true
                                    });

                                    email1.add(Validate.Presence);
                                    email1.add(Validate.Email);
                                </script>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger rounded-3">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif

                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                Salva Email
                            </button>

                        </form>

                    </div>
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">

                        <div class="mb-4">
                            <i class="fa-solid fa-lock fa-2x text-primary"></i>
                        </div>

                        <h4 class="fw-bold mb-3">
                            Modifica Password
                        </h4>

                        @if (session()->has('success'))
                            <div class="alert alert-success rounded-3">
                                {{ session()->get('success') }}
                            </div>
                        @endif

                        <form method="POST" action="mod-pass-stud" onsubmit="modifica_pass()">

                            @csrf

                            {{-- VECCHIA PASSWORD --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Vecchia Password
                                </label>

                                <input type="password" class="form-control rounded-3" id="inputPassword_old"
                                    name="inputPassword_old" maxlength="255">

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" onclick="mostraPassword_old()">

                                    <label class="form-check-label">
                                        Mostra Password
                                    </label>
                                </div>

                                <script type="text/javascript">
                                    var pass_old = new LiveValidation('inputPassword_old', {
                                        onlyOnSubmit: true
                                    });

                                    pass_old.add(Validate.Presence);
                                    pass_old.add(Validate.Pass);
                                </script>

                                @if ($errors->any())
                                    <div class="text-danger small mt-2">
                                        {{ $errors->first('pass0') }}
                                    </div>
                                @endif
                            </div>

                            {{-- NUOVA PASSWORD --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Nuova Password
                                </label>

                                <input type="password" class="form-control rounded-3" id="inputPassword"
                                    name="inputPassword" maxlength="255">

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" onclick="mostraPassword1()">

                                    <label class="form-check-label">
                                        Mostra Password
                                    </label>
                                </div>

                                <script type="text/javascript">
                                    var pass1_ = new LiveValidation('inputPassword', {
                                        onlyOnSubmit: true
                                    });

                                    pass1_.add(Validate.Presence);
                                    pass1_.add(Validate.Pass);
                                </script>

                            </div>

                            {{-- CONFERMA PASSWORD --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Conferma Password
                                </label>

                                <input type="password" class="form-control rounded-3" id="inputPassword2"
                                    name="inputPassword2" maxlength="255">

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" onclick="mostraPassword2()">

                                    <label class="form-check-label">
                                        Mostra Password
                                    </label>
                                </div>

                                <script type="text/javascript">
                                    var pass2_ = new LiveValidation('inputPassword2', {
                                        onlyOnSubmit: true
                                    });

                                    pass2_.add(Validate.Presence);

                                    pass2_.add(Validate.Confirmation, {
                                        match: 'inputPassword'
                                    });
                                </script>

                            </div>

                            {{-- INFO PASSWORD --}}
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
                                    <li>Non più di due lettere o cifre ripetute consecutivamente</li>
                                </ul>

                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                Salva Password
                            </button>

                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
