@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Partita IVA'" />
@endsection

@section('inner')
    <div class="container py-4" style="max-width: 400px;">

        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">Modifica Partita IVA</h5>
            </div>

            <div class="card-body">

                <form method="POST" action="{{ route('admin.account.vat-number.update') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Partita IVA</label>

                        <input type="text" class="form-control @error('piva') is-invalid @enderror" id="piva"
                            name="piva" minlength="11" maxlength="11" value="{{ old('piva', auth()->user()->admin->piva) }}">
                        @error('piva')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Salva
                    </button>

                </form>

            </div>
        </div>

    </div>
@endsection
