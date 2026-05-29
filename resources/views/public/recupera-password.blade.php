@extends('layouts.layout-bootstrap')

@section('content')
    <div class="container py-5">

        {{-- ALERT --}}
        <div class="mb-3">
            @if (session()->has('error'))
                <div class="alert alert-danger">
                    {{ session()->get('error') }}
                </div>
            @endif

            @if (session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
        </div>

        {{-- CARD --}}
        <div class="card shadow-sm mx-auto" style="max-width: 420px;">

            <div class="card-body p-4">

                <h3 class="text-center mb-4">Recupera password</h3>

                <form action="{{ route('password.email') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-12">
                        <label class="form-label">Email</label>

                        <input type="text" class="form-control" id="email" name="email" maxlength="255">

                        <script>
                            var email1 = new LiveValidation('email', {
                                onlyOnSubmit: true
                            });
                            email1.add(Validate.Presence);
                            email1.add(Validate.Email);
                        </script>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary w-100">
                            Recupera password
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
