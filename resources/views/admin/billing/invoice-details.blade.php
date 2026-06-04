@extends('layouts.admin-dashboard')

@section('page-title')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2>Visualizza Fattura</h2>
            </div>
        </div>
    </div>
@endsection

@section('inner')
    <div class="container">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-4">

                <h4 class="fw-bold mb-4">
                    Documento Fattura
                </h4>

                <div class="ratio" style="height: 800px;">

                    <iframe src="/protected-files/{{ $fattura->file_path }}#view=FitH"
                        style="width: 100%; height: 100%; border: 0; border-radius: 12px;">
                    </iframe>

                </div>

            </div>

        </div>

    </div>
@endsection
