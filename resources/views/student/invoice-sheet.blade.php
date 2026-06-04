@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header title="Fattura" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.card>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-1">
                        Fattura
                    </h4>

                    <p class="text-muted mb-0">
                        Visualizzazione documento PDF
                    </p>
                </div>

                <x-ui.primary-button href="{{ route('student.invoices.index') }}" size="sm">
                    Torna alle fatture
                </x-ui.primary-button>
            </div>

            <div class="border rounded-4 overflow-hidden bg-light">
                <iframe class="w-100 border-0" height="800" src="/protected-files/{{ $invoiceSheet->file_path }}#view=FitH">
                </iframe>
            </div>
        </x-ui.card>
    </x-ui.page-section>
@endsection
