@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Fatture'" />
@endsection

@section('inner')
    @php
        use App\Helpers\DateHelper;
        use App\Models\StudentInvoice;

        $fatture0 = StudentInvoice::with('invoice_sheet')
            ->where('student_id', auth()->user()->student->id)
            ->orderByDesc('created_at')
            ->get();
    @endphp

    <x-ui.page-section>
        @if ($fatture0->isNotEmpty())
            <x-ui.table-card title="Fatture disponibili">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Data</th>
                            <th scope="col">Operazioni</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($fatture0 as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>
                                    {{ $item->invoice_sheet?->date ? DateHelper::format($item->invoice_sheet->date) : '-' }}
                                </td>
                                <td>
                                    <x-ui.primary-button
                                        href="{{ route('student.invoice-sheets.show', $item->invoice_sheet_id) }}"
                                        size="sm"
                                    >
                                        Visualizza
                                    </x-ui.primary-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-ui.table-card>
        @else
            <x-ui.empty-state
                title="Non ci sono fatture"
                text="Quando una fattura sara disponibile, la troverai in questa sezione."
            />
        @endif
    </x-ui.page-section>
@endsection
