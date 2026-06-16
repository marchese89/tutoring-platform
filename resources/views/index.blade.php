@extends('layouts.layout-bootstrap')

@section('main-class', 'pb-0')

@push('styles')
    <style>
        .home-page {
            color: #17262c;
            background: #fff;
            overflow-x: clip;
        }

        .home-hero {
            position: relative;
            min-height: min(680px, calc(100vh - 58px));
            display: flex;
            align-items: center;
            overflow: hidden;
            color: #fff;
            background: #16343d;
        }

        .home-hero__image,
        .home-hero__shade {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }

        .home-hero__image {
            object-fit: cover;
            object-position: center 34%;
            filter: saturate(.72) brightness(.48);
        }

        .home-hero__shade {
            background: rgba(8, 33, 41, .42);
        }

        .home-hero__content {
            position: relative;
            z-index: 1;
            max-width: 760px;
            padding-top: 72px;
            padding-bottom: 72px;
        }

        .home-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.25rem;
            color: #bfe9d3;
            font-size: .82rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .home-hero h1 {
            max-width: 720px;
            margin-bottom: 1.25rem;
            font-size: clamp(2.65rem, 5.5vw, 5.25rem);
            line-height: 1.02;
            font-weight: 700;
            letter-spacing: 0;
        }

        .home-hero__lead {
            max-width: 650px;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, .88);
            font-size: 1.15rem;
            line-height: 1.7;
        }

        .home-hero__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .home-hero__actions .btn {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            padding-inline: 1.25rem;
            border-radius: 6px;
            font-weight: 600;
        }

        .home-hero__actions .btn-light {
            color: #123039;
        }

        .home-hero__actions .btn-outline-light:hover {
            color: #123039;
        }

        .home-proof {
            border-bottom: 1px solid #dce5e8;
            background: #eef5f4;
        }

        .home-proof__item {
            display: flex;
            align-items: center;
            gap: .9rem;
            min-height: 112px;
            padding-block: 1.25rem;
        }

        .home-proof__item i {
            color: #087a75;
            font-size: 1.65rem;
        }

        .home-proof__item strong,
        .home-proof__item span {
            display: block;
        }

        .home-proof__item > div {
            min-width: 0;
        }

        .home-proof__item strong {
            margin-bottom: .2rem;
            font-size: .95rem;
        }

        .home-proof__item span {
            color: #5c6d73;
            font-size: .86rem;
            overflow-wrap: anywhere;
        }

        .home-section {
            padding-block: 88px;
        }

        .home-section--muted {
            background: #f3f6f7;
        }

        .home-section--dark {
            color: #fff;
            background: #17343d;
        }

        .home-section__eyebrow {
            margin-bottom: .75rem;
            color: #087a75;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .home-section--dark .home-section__eyebrow {
            color: #8dd9b7;
        }

        .home-section h2 {
            max-width: 720px;
            margin-bottom: 1rem;
            font-size: clamp(2rem, 4vw, 3.25rem);
            line-height: 1.12;
            font-weight: 700;
            letter-spacing: 0;
        }

        .home-section__intro {
            max-width: 680px;
            margin-bottom: 3rem;
            color: #607077;
            font-size: 1.05rem;
            line-height: 1.75;
        }

        .home-section--dark .home-section__intro {
            color: rgba(255, 255, 255, .72);
        }

        .home-service {
            padding: 1.75rem 1.25rem 1.5rem 0;
            border-top: 2px solid #1a4c59;
        }

        .home-service__icon {
            width: 44px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            border-radius: 6px;
            color: #087a75;
            background: #e5f4ef;
            font-size: 1.25rem;
        }

        .home-service h3 {
            margin-bottom: .75rem;
            font-size: 1.15rem;
            font-weight: 700;
        }

        .home-service p {
            max-width: 330px;
            margin: 0;
            color: #65757b;
            line-height: 1.65;
        }

        .home-method__statement {
            max-width: 760px;
            margin: 0;
            font-size: clamp(1.55rem, 3vw, 2.35rem);
            line-height: 1.45;
            font-weight: 600;
        }

        .home-method__steps {
            display: grid;
            gap: 1.5rem;
        }

        .home-method__step {
            display: grid;
            grid-template-columns: 42px 1fr;
            gap: 1rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, .18);
        }

        .home-method__step span {
            color: #8dd9b7;
            font-weight: 700;
        }

        .home-method__step h3 {
            margin-bottom: .4rem;
            font-size: 1rem;
            font-weight: 700;
        }

        .home-method__step p {
            margin: 0;
            color: rgba(255, 255, 255, .68);
            font-size: .92rem;
            line-height: 1.6;
        }

        .home-price {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            padding: 1.75rem;
            border: 1px solid #dce5e8;
            border-radius: 8px;
            background: #fff;
        }

        .home-price__label {
            margin-bottom: .45rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .home-price__description {
            max-width: 280px;
            margin: 0;
            color: #66767c;
            line-height: 1.55;
        }

        .home-price__amount {
            flex: 0 0 auto;
            text-align: right;
        }

        .home-price__amount strong,
        .home-price__amount span {
            display: block;
        }

        .home-price__amount strong {
            color: #087a75;
            font-size: 2rem;
        }

        .home-price__amount span {
            color: #718087;
            font-size: .78rem;
        }

        .home-profile {
            display: grid;
            grid-template-columns: minmax(220px, 360px) 1fr;
            gap: clamp(2rem, 6vw, 6rem);
            align-items: center;
        }

        .home-profile__photo {
            aspect-ratio: 4 / 5;
            width: 100%;
            object-fit: cover;
            object-position: center 30%;
            border-radius: 8px;
        }

        .home-profile__credential {
            display: flex;
            align-items: flex-start;
            gap: .8rem;
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid #dce5e8;
        }

        .home-profile__credential i {
            color: #087a75;
            font-size: 1.35rem;
        }

        .home-profile__credential strong,
        .home-profile__credential span {
            display: block;
        }

        .home-profile__credential span {
            margin-top: .25rem;
            color: #66767c;
        }

        .home-reviews__summary {
            display: flex;
            align-items: center;
            gap: .8rem;
            margin-bottom: 2.5rem;
        }

        .home-reviews__score {
            font-size: 2rem;
            font-weight: 700;
        }

        .home-reviews__stars,
        .home-review__rating {
            color: #d69019;
        }

        .home-review {
            padding: 1.5rem;
            border: 1px solid #dce5e8;
            border-radius: 8px;
            background: #fff;
        }

        .home-review blockquote {
            margin: 1rem 0 1.5rem;
            color: #33474e;
            line-height: 1.7;
        }

        .home-review p {
            margin: 0;
            font-size: .88rem;
            font-weight: 700;
        }

        .home-contact {
            padding-block: 72px;
            color: #fff;
            background: #087a75;
        }

        .home-contact h2 {
            max-width: 720px;
            margin-bottom: 1rem;
            font-size: clamp(2rem, 4vw, 3.25rem);
            font-weight: 700;
            letter-spacing: 0;
        }

        .home-contact p {
            max-width: 660px;
            margin-bottom: 2rem;
            color: rgba(255, 255, 255, .82);
            line-height: 1.7;
        }

        .home-contact__actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .home-contact__actions .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            border-radius: 6px;
            font-weight: 600;
        }

        @media (max-width: 767.98px) {
            .home-hero {
                min-height: 620px;
                align-items: flex-end;
            }

            .home-hero__image {
                object-position: 58% center;
            }

            .home-hero__content {
                padding-top: 110px;
                padding-bottom: 48px;
            }

            .home-hero h1 {
                font-size: 2.65rem;
            }

            .home-hero__actions .btn {
                width: 100%;
            }

            .home-proof__item {
                min-height: auto;
                border-bottom: 1px solid #dce5e8;
            }

            .home-section {
                padding-block: 64px;
            }

            .home-profile {
                grid-template-columns: 1fr;
            }

            .home-profile__photo {
                max-height: 520px;
            }

            .home-price {
                align-items: flex-start;
                flex-direction: column;
            }

            .home-price__amount {
                text-align: left;
            }
        }
    </style>
@endpush

@section('content')
    <div class="home-page">
        <section class="home-hero">
            <img class="home-hero__image" src="{{ asset('images/computer-science-tutoring-hero.jpg') }}"
                alt="{{ __('public.home.hero_alt') }}">
            <div class="home-hero__shade" aria-hidden="true"></div>

            <div class="container home-hero__content">
                <p class="home-eyebrow">
                    <i class="bi bi-code-square" aria-hidden="true"></i>
                    {{ __('public.home.hero_eyebrow') }}
                </p>
                <h1>{{ __('public.home.hero_title') }}</h1>
                <p class="home-hero__lead">
                    {{ __('public.home.hero_lead') }}
                </p>
                <div class="home-hero__actions">
                    <a class="btn btn-light" href="{{ route('theme-areas.index') }}">
                        {{ __('public.home.explore_courses') }}
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-outline-light" href="{{ route('lesson-requests.create') }}">
                        {{ __('public.home.request_material') }}
                    </a>
                </div>
            </div>
        </section>

        <section class="home-proof" aria-label="{{ __('public.home.strengths_label') }}">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="home-proof__item">
                            <i class="bi bi-mortarboard" aria-hidden="true"></i>
                            <div>
                                <strong>{{ __('public.home.strengths.academic_title') }}</strong>
                                <span>{{ __('public.home.strengths.academic_text') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="home-proof__item">
                            <i class="bi bi-person-check" aria-hidden="true"></i>
                            <div>
                                <strong>{{ __('public.home.strengths.individual_title') }}</strong>
                                <span>{{ __('public.home.strengths.individual_text') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="home-proof__item">
                            <i class="bi bi-file-earmark-code" aria-hidden="true"></i>
                            <div>
                                <strong>{{ __('public.home.strengths.material_title') }}</strong>
                                <span>{{ __('public.home.strengths.material_text') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section">
            <div class="container">
                <p class="home-section__eyebrow">{{ __('public.home.services.eyebrow') }}</p>
                <h2>{{ __('public.home.services.title') }}</h2>
                <p class="home-section__intro">
                    {{ __('public.home.services.intro') }}
                </p>

                <div class="row g-4">
                    <div class="col-md-4">
                        <x-service-card icon="bi-easel2" :title="__('public.home.services.online_title')"
                            :description="__('public.home.services.online_text')" />
                    </div>
                    <div class="col-md-4">
                        <x-service-card icon="bi-terminal" :title="__('public.home.services.exercises_title')"
                            :description="__('public.home.services.exercises_text')" />
                    </div>
                    <div class="col-md-4">
                        <x-service-card icon="bi-compass" :title="__('public.home.services.method_title')"
                            :description="__('public.home.services.method_text')" />
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section home-section--dark">
            <div class="container">
                <div class="row g-5 align-items-start">
                    <div class="col-lg-7">
                        <p class="home-section__eyebrow">{{ __('public.home.method.eyebrow') }}</p>
                        <p class="home-method__statement">
                            {{ __('public.home.method.statement') }}
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <div class="home-method__steps">
                            <div class="home-method__step">
                                <span>01</span>
                                <div>
                                    <h3>{{ __('public.home.method.step_one_title') }}</h3>
                                    <p>{{ __('public.home.method.step_one_text') }}</p>
                                </div>
                            </div>
                            <div class="home-method__step">
                                <span>02</span>
                                <div>
                                    <h3>{{ __('public.home.method.step_two_title') }}</h3>
                                    <p>{{ __('public.home.method.step_two_text') }}</p>
                                </div>
                            </div>
                            <div class="home-method__step">
                                <span>03</span>
                                <div>
                                    <h3>{{ __('public.home.method.step_three_title') }}</h3>
                                    <p>{{ __('public.home.method.step_three_text') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section home-section--muted">
            <div class="container">
                <div class="row g-5 align-items-end mb-5">
                    <div class="col-lg-8">
                        <p class="home-section__eyebrow">{{ __('public.home.pricing.eyebrow') }}</p>
                        <h2>{{ __('public.home.pricing.title') }}</h2>
                    </div>
                    <div class="col-lg-4">
                        <p class="home-section__intro mb-0">
                            {{ __('public.home.pricing.intro') }}
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-6">
                        <x-price-card :title="__('public.home.pricing.high_school_title')" price="15 €"
                            :unit="__('public.home.pricing.hour')" :description="__('public.home.pricing.high_school_text')" />
                    </div>
                    <div class="col-lg-6">
                        <x-price-card :title="__('public.home.pricing.university_title')" price="20 €"
                            :unit="__('public.home.pricing.hour')" :description="__('public.home.pricing.university_text')" />
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section">
            <div class="container">
                <div class="home-profile">
                    @if ($admin?->photo_path)
                        <img class="home-profile__photo" src="{{ $admin->photo_path }}"
                            alt="{{ __('public.home.profile.photo_alt') }}">
                    @endif

                    <div>
                        <p class="home-section__eyebrow">{{ __('public.home.profile.eyebrow') }}</p>
                        <h2>{{ __('public.home.profile.title') }}</h2>
                        <p class="home-section__intro mb-0">
                            {{ __('public.home.profile.intro') }}
                        </p>
                        <div class="home-profile__credential">
                            <i class="bi bi-patch-check" aria-hidden="true"></i>
                            <div>
                                <strong>{{ __('public.home.profile.degree') }}</strong>
                                <span>{{ __('public.home.profile.degree_result') }}</span>
                            </div>
                        </div>
                        <a class="btn btn-outline-primary mt-4" href="{{ route('about') }}">
                            {{ __('public.home.profile.learn_more') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        @if ($reviews->isNotEmpty())
            <section class="home-section home-section--muted">
                <div class="container">
                    <p class="home-section__eyebrow">{{ __('public.home.reviews.eyebrow') }}</p>
                    <h2>{{ __('public.home.reviews.title') }}</h2>

                    @if ($averageRating)
                        <div class="home-reviews__summary">
                            <span class="home-reviews__score">
                                {{ \App\Helpers\NumberHelper::format($averageRating, 1) }}
                            </span>
                            <div>
                                <div class="home-reviews__stars"
                                    aria-label="{{ __('public.home.reviews.average_aria', ['rating' => $averageRating]) }}">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <i class="bi {{ $star <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"
                                            aria-hidden="true"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">{{ __('public.home.reviews.average_label') }}</small>
                            </div>
                        </div>
                    @endif

                    <div class="row g-4">
                        @foreach ($reviews as $review)
                            <div class="col-md-6 col-xl-4">
                                <x-testimonial-card :name="$review->student->user->name . ' ' . $review->student->user->surname"
                                    :text="$review->review" :rating="$review->rating" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <section class="home-contact">
            <div class="container">
                <h2>{{ __('public.home.contact.title') }}</h2>
                <p>
                    {{ __('public.home.contact.text') }}
                </p>
                <div class="home-contact__actions">
                    <a class="btn btn-light" href="https://api.whatsapp.com/send?phone=3272991334">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                        {{ __('public.home.contact.whatsapp') }}
                    </a>
                    <a class="btn btn-outline-light" href="mailto:marchese89@hotmail.com">
                        <i class="bi bi-envelope" aria-hidden="true"></i>
                        {{ __('public.home.contact.email') }}
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
