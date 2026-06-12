@extends('layouts.layout-bootstrap')

@section('content')

    <div class="container py-5">

        {{-- Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- CARD --}}
        <div class="card shadow-sm mx-auto" style="max-width: 420px;">

            <div class="card-body p-4">

                <h3 class="text-center mb-4">Reset password</h3>

                <form method="POST" action="{{ route('password.update') }}" class="row g-3">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- EMAIL --}}
                    <div class="col-12">
                        <label class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" placeholder="Email">
                    </div>

                    {{-- PASSWORD --}}
                    <div class="col-12">
                        <x-ui.password-field name="password" label="Nuova password" wrapper-class="mb-0"
                            placeholder="Nuova password" autocomplete="new-password" required />
                    </div>

                    {{-- CONFIRM --}}
                    <div class="col-12">
                        <x-ui.password-field name="password_confirmation" label="Conferma password" wrapper-class="mb-0"
                            placeholder="Conferma password" autocomplete="new-password" required />
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary w-100">
                            Cambia password
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection
