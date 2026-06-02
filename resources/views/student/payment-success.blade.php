@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Pagamento completato'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <x-ui.card>
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="fa-solid fa-circle-check fa-3x text-success"></i>
                        </div>

                        <h4 class="fw-bold mb-2">
                            Fattura creata
                        </h4>

                        <p class="text-muted mb-4">
                            Il pagamento e la generazione della fattura sono stati completati correttamente.
                        </p>

                        <x-ui.primary-button href="{{ route('student.dashboard') }}">
                            Torna alla dashboard
                        </x-ui.primary-button>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
