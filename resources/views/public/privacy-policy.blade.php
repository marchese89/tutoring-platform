@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <x-ui.card body-class="p-4 p-lg-5">
                    <article>
                        <h1 class="h2 fw-bold">{{ __('legal.privacy.title') }}</h1>

                        <p class="text-muted">{{ __('legal.privacy.last_updated') }}</p>

                        <p>{{ __('legal.privacy.intro') }}</p>

                        @foreach (__('legal.privacy.sections') as $section)
                            <h3>{{ $section['title'] }}</h3>

                            @foreach ($section['body'] as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        @endforeach
                    </article>
                </x-ui.card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
