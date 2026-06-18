@extends('layouts.layout-bootstrap')

@section('content')
    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-4 col-md-6">

                <div class="card shadow-sm border-0 rounded-4">

                    <div class="card-body p-4">

                        <div class="text-center mb-4">
                            <h2 class="fw-bold mb-1">{{ __('auth.login.title') }}</h2>
                            <p class="text-muted mb-0">
                                {{ __('auth.login.subtitle') }}
                            </p>
                        </div>

                        {{-- Global alerts --}}
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

                            <input type="hidden" name="return" value="{{ $returnToLessonRequest ? '1' : '0' }}">

                            {{-- EMAIL --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    {{ __('auth.fields.email') }}
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
                            <x-ui.password-field name="password" :label="__('auth.fields.password')" wrapper-class="mb-3"
                                autocomplete="current-password" required />

                            {{-- Password recovery --}}
                            <div class="text-end mb-4">

                                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                    {{ __('auth.login.forgot_password') }}
                                </a>

                            </div>

                            {{-- SUBMIT --}}
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('auth.login.submit') }}
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
