@extends('layouts.layout-bootstrap')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-4 col-md-6">

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h2 class="fw-bold mb-1">Login</h2>
                            <p class="text-muted mb-0">
                                Accedi al tuo account
                            </p>
                        </div>

                        {{-- ALERT GLOBALI --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">

                            @csrf

                            <input type="hidden" name="return" value="{{ request('back') ? '1' : '0' }}">

                            {{-- EMAIL --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Email
                                </label>

                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- PASSWORD --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Password
                                </label>

                                <div class="input-group">

                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror">

                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        Mostra
                                    </button>

                                </div>

                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- RECUPERO PASSWORD --}}
                            <div class="text-end mb-4">

                                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                    Recupera password
                                </a>

                            </div>

                            {{-- SUBMIT --}}
                            <button type="submit" class="btn btn-primary w-100">
                                Accedi
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        document.getElementById('togglePassword')
            .addEventListener('click', function() {

                const password = document.getElementById('password');

                if (password.type === 'password') {
                    password.type = 'text';
                    this.innerText = 'Nascondi';
                } else {
                    password.type = 'password';
                    this.innerText = 'Mostra';
                }
            });
    </script>
@endsection
