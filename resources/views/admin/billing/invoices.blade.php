@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.billing.invoice_list_title')" />
@endsection

@section('inner')
    <x-ui.page-section>

        @if ($invoices->count() > 0)
            <x-ui.table-card :title="__('admin.billing.invoices')">
                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>{{ __('admin.billing.number') }}</th>
                            <th>{{ __('ui.table.date') }}</th>
                            <th>{{ __('ui.table.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($invoices as $item)
                            <tr>

                                <td>
                                    {{ $item->number }}
                                </td>

                                <td>
                                    {{ $item->date }}
                                </td>

                                <td>
                                    @if ($item->showUrl)
                                        <x-ui.primary-button href="{{ $item->showUrl }}">
                                            {{ __('ui.table.view') }}
                                        </x-ui.primary-button>
                                    @else
                                        <span class="text-muted">{{ __('admin.billing.unavailable') }}</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

                <x-ui.pagination :paginator="$invoices" />
            </x-ui.table-card>
        @else
            <x-ui.empty-state :title="__('admin.billing.no_invoices')" />
        @endif

    </x-ui.page-section>
@endsection
