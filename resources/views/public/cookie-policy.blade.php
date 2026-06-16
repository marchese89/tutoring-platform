@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <x-ui.card body-class="p-4 p-lg-5">
                    <article>
                        <h1 class="h2 fw-bold text-center">{{ __('legal.cookie.title') }}</h1>

                        @foreach (__('legal.cookie.intro') as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach

                        <h3>{{ __('legal.cookie.what_are_title') }}</h3>
                        <p>{{ __('legal.cookie.what_are_text') }}</p>

                        <h3>{{ __('legal.cookie.technical_title') }}</h3>
                        <p>{{ __('legal.cookie.technical_text') }}</p>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                @foreach (__('legal.cookie.technical_cookies') as $name => $description)
                                    <tr>
                                        <td><strong>{{ $name }}:</strong></td>
                                        <td>{{ $description }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                        <h3>{{ __('legal.cookie.profiling_title') }}</h3>
                        <p>{{ __('legal.cookie.profiling_text') }}</p>

                        <h3>{{ __('legal.cookie.management_title') }}</h3>
                        <p>{{ __('legal.cookie.management_text') }}</p>

                        <h3>{{ __('legal.cookie.rights_title') }}</h3>

                        <ul>
                            @foreach (__('legal.cookie.rights') as $right)
                                <li>{{ $right }}</li>
                            @endforeach
                        </ul>

                        <p>{{ __('legal.cookie.contact_text') }}</p>
                    </article>
                </x-ui.card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
