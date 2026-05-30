@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Foto'" />
@endsection

@section('inner')
    <div class="container py-4" style="max-width: 600px;">

        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">Foto profilo amministratore</h5>
            </div>

            <div class="card-body text-center">

                <div class="mb-4">
                    <img alt="Nessuna foto caricata" src="{{ auth()->user()->admin->photo }}"
                        class="img-fluid rounded shadow-sm" style="max-width: 300px; height: auto;">
                </div>

                <form method="POST" action="{{ route('admin.account.photo.update') }}" enctype="multipart/form-data" id="upload">

                    @csrf

                    <div class="mb-3 text-start">
                        <label class="form-label">Seleziona immagine</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>

                    <div class="progress mb-3" id="progressbar" style="display:none; height: 20px;">
                        <div class="progress-bar" id="percent" style="width: 0%;">
                            0%
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100"
                        onclick="upload('upload','file','{{ route('admin.account.photo.update') }}',1)">
                        Carica foto
                    </button>

                </form>

            </div>

        </div>

    </div>
@endsection
