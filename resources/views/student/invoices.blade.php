@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.invoices.title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        @if ($invoices->count() > 0)
            <x-ui.table-card :title="__('student.invoices.available')">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('student.invoices.order') }}</th>
                            <th scope="col">{{ __('ui.table.date') }}</th>
                            <th scope="col">{{ __('ui.table.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($invoices as $item)
                            <tr>
                                <th scope="row">{{ $item['number'] ?? $item['id'] }}</th>
                                <td>
                                    {{ $item['order_id'] ? '#' . $item['order_id'] : __('student.invoices.extra_payment') }}
                                </td>
                                <td>
                                    {{ $item['date'] }}
                                </td>
                                <td>
                                    <x-ui.primary-button
                                        href="{{ $item['show_url'] }}"
                                        size="sm"
                                    >
                                        {{ __('ui.table.view') }}
                                    </x-ui.primary-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                    <x-ui.pagination :paginator="$invoices" />
            </x-ui.table-card>
        @else
            <x-ui.empty-state :title="__('student.invoices.empty_title')" :text="__('student.invoices.empty_text')" />
        @endif
    </x-ui.page-section>
@endsection
