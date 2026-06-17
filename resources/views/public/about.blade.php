@extends('layouts.layout-bootstrap')

@section('main-class', 'pb-0')

@push('styles')
    <style>
        .about-page {
            color: #182a31;
            background: #fff;
            overflow-x: clip;
        }

        .about-hero {
            padding-block: clamp(4rem, 8vw, 7rem);
            color: #fff;
            background: linear-gradient(135deg, #17343d 0%, #087a75 100%);
        }

        .about-hero__content {
            max-width: 760px;
        }

        .about-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1rem;
            color: #bfe9d3;
            font-size: .8rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .about-hero h1 {
            max-width: 760px;
            margin-bottom: 1.25rem;
            font-size: clamp(2.5rem, 5vw, 4.5rem);
            line-height: 1.05;
            font-weight: 700;
            letter-spacing: 0;
            overflow-wrap: anywhere;
        }

        .about-hero p {
            max-width: 690px;
            margin: 0;
            color: rgba(255, 255, 255, .82);
            font-size: 1.1rem;
            line-height: 1.75;
        }

        .about-section {
            padding-block: 80px;
        }

        .about-section--muted {
            background: #f3f6f7;
        }

        .about-profile {
            display: grid;
            grid-template-columns: minmax(220px, 360px) 1fr;
            gap: clamp(2rem, 6vw, 5.5rem);
            align-items: center;
        }

        .about-profile__photo {
            aspect-ratio: 4 / 5;
            width: 100%;
            object-fit: cover;
            object-position: center 30%;
            border-radius: 8px;
        }

        .about-section__eyebrow {
            margin-bottom: .75rem;
            color: #087a75;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .about-section h2 {
            max-width: 720px;
            margin-bottom: 1rem;
            font-size: clamp(2rem, 4vw, 3.15rem);
            line-height: 1.12;
            font-weight: 700;
            letter-spacing: 0;
            overflow-wrap: anywhere;
        }

        .about-section__text {
            max-width: 720px;
            color: #5f7077;
            font-size: 1.02rem;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .about-fact-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .about-fact {
            min-height: 132px;
            padding: 1.35rem;
            border: 1px solid #dce5e8;
            border-radius: 8px;
            background: #fff;
        }

        .about-fact i {
            color: #087a75;
            font-size: 1.4rem;
        }

        .about-fact strong,
        .about-fact span {
            display: block;
        }

        .about-fact strong {
            margin-top: 1rem;
            margin-bottom: .4rem;
            font-size: .98rem;
        }

        .about-fact span {
            color: #66767c;
            font-size: .9rem;
            line-height: 1.55;
        }

        .about-method {
            display: grid;
            gap: 1rem;
        }

        .about-method__item {
            display: grid;
            grid-template-columns: 48px 1fr;
            gap: 1rem;
            padding: 1.25rem 0;
            border-top: 1px solid #dce5e8;
        }

        .about-method__item span {
            color: #087a75;
            font-weight: 700;
        }

        .about-method__item h3 {
            margin-bottom: .4rem;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .about-method__item p {
            margin: 0;
            color: #66767c;
            line-height: 1.65;
        }

        .about-certificates {
            display: grid;
            gap: 1.5rem;
        }

        .about-certificate-title {
            font-size: 1.15rem;
            font-weight: 700;
        }

        @media (max-width: 767.98px) {
            .about-section {
                padding-block: 60px;
            }

            .about-profile,
            .about-fact-grid {
                grid-template-columns: 1fr;
            }

            .about-hero h1 {
                font-size: 2.3rem;
            }

            .about-hero p {
                max-width: 350px;
                font-size: .98rem;
            }

            .about-section h2 {
                max-width: 350px;
                font-size: 1.35rem;
            }

            .about-section__text {
                max-width: 350px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="about-page">
        <section class="about-hero">
            <div class="container">
                <div class="about-hero__content">
                    <p class="about-eyebrow">
                        <i class="bi bi-person-check" aria-hidden="true"></i>
                        {{ __('public.about.eyebrow') }}
                    </p>
                    <h1>{{ __('public.about.title') }}</h1>
                    <p>{{ __('public.about.intro') }}</p>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <div class="about-profile">
                    @if ($adminPhotoUrl)
                        <img class="about-profile__photo" src="{{ $adminPhotoUrl }}"
                            alt="{{ __('public.about.photo_alt') }}">
                    @endif

                    <div>
                        <p class="about-section__eyebrow">{{ __('public.about.profile_eyebrow') }}</p>
                        <h2>{{ __('public.about.profile_title') }}</h2>
                        <p class="about-section__text">{{ __('public.about.profile_text') }}</p>

                        <div class="about-fact-grid">
                            <div class="about-fact">
                                <i class="bi bi-mortarboard" aria-hidden="true"></i>
                                <strong>{{ __('public.about.facts.degree_title') }}</strong>
                                <span>{{ __('public.about.facts.degree_text') }}</span>
                            </div>
                            <div class="about-fact">
                                <i class="bi bi-diagram-3" aria-hidden="true"></i>
                                <strong>{{ __('public.about.facts.method_title') }}</strong>
                                <span>{{ __('public.about.facts.method_text') }}</span>
                            </div>
                            <div class="about-fact">
                                <i class="bi bi-file-earmark-check" aria-hidden="true"></i>
                                <strong>{{ __('public.about.facts.certificates_title') }}</strong>
                                <span>{{ trans_choice('public.about.facts.certificates_text', $certificateCount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section about-section--muted">
            <div class="container">
                <div class="row g-5 align-items-start">
                    <div class="col-lg-6">
                        <p class="about-section__eyebrow">{{ __('public.about.method_eyebrow') }}</p>
                        <h2>{{ __('public.about.method_title') }}</h2>
                        <p class="about-section__text">{{ __('public.about.method_text') }}</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-method">
                            <div class="about-method__item">
                                <span>01</span>
                                <div>
                                    <h3>{{ __('public.about.method_steps.diagnosis_title') }}</h3>
                                    <p>{{ __('public.about.method_steps.diagnosis_text') }}</p>
                                </div>
                            </div>
                            <div class="about-method__item">
                                <span>02</span>
                                <div>
                                    <h3>{{ __('public.about.method_steps.practice_title') }}</h3>
                                    <p>{{ __('public.about.method_steps.practice_text') }}</p>
                                </div>
                            </div>
                            <div class="about-method__item">
                                <span>03</span>
                                <div>
                                    <h3>{{ __('public.about.method_steps.review_title') }}</h3>
                                    <p>{{ __('public.about.method_steps.review_text') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <div class="row justify-content-between align-items-end g-4 mb-4">
                    <div class="col-lg-8">
                        <p class="about-section__eyebrow">{{ __('public.about.certificates_eyebrow') }}</p>
                        <h2>{{ __('public.about.certificates_title') }}</h2>
                    </div>
                    <div class="col-lg-4">
                        <p class="about-section__text mb-0">{{ __('public.about.certificates_text') }}</p>
                    </div>
                </div>

                <div class="about-certificates">
                    @forelse ($certificates as $certificate)
                        <x-ui.card>
                            <div class="about-certificate-title mb-4">
                                {{ $certificate->name }}
                            </div>

                            @if ($certificate->file_path)
                                <x-ui.pdf-viewer :src="$certificate->file_path"
                                    :title="__('public.about.certificate_preview', ['name' => $certificate->name])"
                                    size="compact" />
                            @endif
                        </x-ui.card>
                    @empty
                        <x-ui.empty-state :title="__('public.about.empty_title')" :text="__('public.about.empty_text')" />
                    @endforelse
                </div>
            </div>
        </section>
    </div>
@endsection
