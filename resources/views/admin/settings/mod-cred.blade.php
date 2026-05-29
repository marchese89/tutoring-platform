@extends('layouts.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Modifica Credenziali'" />
@endsection

@section('inner')
    <div class="container py-4" style="max-width: 700px;">

        {{-- EMAIL --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Modifica Email</h5>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('admin.account.email.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="inputEmail" name="inputEmail" maxlength="255"
                            value="{{ auth()->user()->email }}">

                        @if ($errors->any())
                            <small class="text-danger">
                                {{ $errors->first('email') }}
                            </small>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Aggiorna Email
                    </button>
                </form>
            </div>
        </div>


        {{-- PASSWORD --}}
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Modifica Password</h5>
            </div>

            <div class="card-body">

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.account.password.update') }}">
                    @csrf

                    {{-- vecchia password --}}
                    <div class="mb-3">
                        <label class="form-label">Vecchia password</label>
                        <input type="password" class="form-control" id="inputPassword_old" name="inputPassword_old">

                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword_old()" id="showOld">
                            <label class="form-check-label" for="showOld">
                                Mostra password
                            </label>
                        </div>

                        @if ($errors->any())
                            <small class="text-danger">
                                {{ $errors->first('pass0') }}
                            </small>
                        @endif
                    </div>

                    {{-- nuova password --}}
                    <div class="mb-3">
                        <label class="form-label">Nuova password</label>
                        <input type="password" class="form-control" id="inputPassword" name="inputPassword">

                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword1()" id="showNew">
                            <label class="form-check-label" for="showNew">
                                Mostra password
                            </label>
                        </div>
                    </div>

                    {{-- conferma --}}
                    <div class="mb-3">
                        <label class="form-label">Conferma password</label>
                        <input type="password" class="form-control" id="inputPassword2" name="inputPassword2">

                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword2()" id="showConfirm">
                            <label class="form-check-label" for="showConfirm">
                                Mostra password
                            </label>
                        </div>
                    </div>

                    {{-- regole password --}}
                    <div class="alert alert-secondary small">
                        Password: almeno 10 caratteri, una maiuscola, una minuscola, un numero e un carattere speciale (@ #
                        ! ? . , ; :).
                        Evitare più di 2 caratteri uguali consecutivi.
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Aggiorna Password
                    </button>

                </form>
            </div>
        </div>

    </div>
@endsection
