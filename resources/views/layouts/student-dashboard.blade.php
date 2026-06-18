@extends('layouts.layout-bootstrap')

@section('content')
    <main>
        @if (!Request::is('student/dashboard'))
            @yield('page-title')
            <div class="container">
                {{ Breadcrumbs::render() }}
            </div>
            @yield('inner')
        @else
            <x-ui.section-header :title="__('student.dashboard.title')" />

            <div class="container">
                <div class="row g-4">
                    <x-ui.card-item :title="__('student.dashboard.account_title')"
                        :text="__('student.dashboard.account_text')"
                        :url="route('student.account')" />

                    <x-ui.card-item :title="__('student.dashboard.courses_title')"
                        :text="__('student.dashboard.courses_text')"
                        :url="route('student.courses.index')" />

                    <x-ui.card-item :title="__('student.dashboard.direct_requests_title')"
                        :text="__('student.dashboard.direct_requests_text')"
                        :url="route('student.direct-requests.index')" />

                    <x-ui.card-item :title="__('student.dashboard.requested_lessons_title')"
                        :text="__('student.dashboard.requested_lessons_text')"
                        :url="route('student.direct-requests.purchased')" />

                    <x-ui.card-item :title="__('student.dashboard.orders_title')"
                        :text="__('student.dashboard.orders_text')"
                        :url="route('student.orders.index')" />

                    <x-ui.card-item :title="__('student.dashboard.review_title')"
                        :text="__('student.dashboard.review_text')"
                        :url="route('student.review')" />

                    <x-ui.card-item :title="__('student.dashboard.extra_payment_title')"
                        :text="__('student.dashboard.extra_payment_text')"
                        :url="route('payment.extra')" />

                    <x-ui.card-item :title="__('student.dashboard.invoices_title')"
                        :text="__('student.dashboard.invoices_text')"
                        :url="route('student.invoices.index')" />
                </div>
            </div>
        @endif
    </main>
@endsection
