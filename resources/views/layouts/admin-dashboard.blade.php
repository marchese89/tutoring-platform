@extends('layouts.layout-bootstrap')

@section('content')
    @if (!Request::is('admin/dashboard'))
        @yield('page-title')

        <div class="container">
            {{ Breadcrumbs::render() }}
        </div>

        @yield('inner')
    @else
        <x-ui.section-header :title="__('admin.dashboard.title')" />
        <div class="container">
            <div class="row g-4">
                <x-ui.card-item :title="__('admin.dashboard.account_title')"
                    :text="__('admin.dashboard.account_text')"
                    :url="route('admin.account')" />

                <x-ui.card-item :title="__('admin.dashboard.teaching_title')"
                    :text="__('admin.dashboard.teaching_text')"
                    :url="route('admin.teaching.index')" />

                <x-ui.card-item :title="__('admin.dashboard.students_title')"
                    :text="__('admin.dashboard.students_text')"
                    :url="route('admin.students.index')" />

                <x-ui.card-item :title="__('admin.dashboard.sales_title')"
                    :text="__('admin.dashboard.sales_text')"
                    :url="route('admin.sales.index')" />

                <x-ui.card-item :title="__('admin.dashboard.extra_invoice_title')"
                    :text="__('admin.dashboard.extra_invoice_text')"
                    :url="route('admin.invoices.extra')" />

                <x-ui.card-item :title="__('admin.dashboard.invoices_title')"
                    :text="__('admin.dashboard.invoices_text')"
                    :url="route('admin.invoices.index')" />
            </div>
        </div>
    @endif
@endsection
