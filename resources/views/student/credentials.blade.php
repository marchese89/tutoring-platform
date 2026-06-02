@extends('layouts.student-dashboard')

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

                        <form method="POST" action="{{ route('student.account.email.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    Nuova Email
                                </label>

                                <input type="email" class="form-control rounded-3 @error('inputEmail') is-invalid @enderror"
                                    id="inputEmail" name="inputEmail" maxlength="255"
                                    value="{{ old('inputEmail', auth()->user()->email) }}">
                                @error('inputEmail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

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

                        <form method="POST" action="{{ route('student.account.password.update') }}">

                            @csrf

                            {{-- VECCHIA PASSWORD --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Vecchia Password
                                </label>

                                <input type="password"
                                    class="form-control rounded-3 @error('inputPassword_old') is-invalid @enderror"
                                    id="inputPassword_old" name="inputPassword_old" maxlength="255">
                                @error('inputPassword_old')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" onclick="mostraPassword_old()">

                                    <label class="form-check-label">
                                        Mostra Password
                                    </label>
                                </div>

                            </div>

                            {{-- NUOVA PASSWORD --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Nuova Password
                                </label>

                                <input type="password"
                                    class="form-control rounded-3 @error('inputPassword') is-invalid @enderror"
                                    id="inputPassword" name="inputPassword" maxlength="255">
                                @error('inputPassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" onclick="mostraPassword1()">

                                    <label class="form-check-label">
                                        Mostra Password
                                    </label>
                                </div>

                            </div>

                            {{-- CONFERMA PASSWORD --}}
                            <div class="mb-4">

                                <label class="form-label fw-semibold">
                                    Conferma Password
                                </label>

                                <input type="password"
                                    class="form-control rounded-3 @error('inputPassword2') is-invalid @enderror"
                                    id="inputPassword2" name="inputPassword2" maxlength="255">
                                @error('inputPassword2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" onclick="mostraPassword2()">

                                    <label class="form-check-label">
                                        Mostra Password
                                    </label>
                                </div>

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
