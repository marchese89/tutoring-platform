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
                alt="Sessione individuale di studio e programmazione">
            <div class="home-hero__shade" aria-hidden="true"></div>

            <div class="container home-hero__content">
                <p class="home-eyebrow">
                    <i class="bi bi-code-square" aria-hidden="true"></i>
                    Lezioni individuali per superiori e università
                </p>
                <h1>Lezioni private di informatica</h1>
                <p class="home-hero__lead">
                    Un percorso pratico per comprendere gli argomenti, affrontare gli esercizi con metodo e diventare
                    autonomi nello studio.
                </p>
                <div class="home-hero__actions">
                    <a class="btn btn-light" href="{{ route('theme-areas.index') }}">
                        Esplora i corsi
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a class="btn btn-outline-light" href="{{ route('lesson-requests.create') }}">
                        Richiedi materiale
                    </a>
                </div>
            </div>
        </section>

        <section class="home-proof" aria-label="Punti di forza">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="home-proof__item">
                            <i class="bi bi-mortarboard" aria-hidden="true"></i>
                            <div>
                                <strong>Preparazione accademica</strong>
                                <span>Laurea Magistrale in Ingegneria Informatica, 110/110</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="home-proof__item">
                            <i class="bi bi-person-check" aria-hidden="true"></i>
                            <div>
                                <strong>Percorso individuale</strong>
                                <span>Lezioni costruite sul livello e sugli obiettivi dello studente</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="home-proof__item">
                            <i class="bi bi-file-earmark-code" aria-hidden="true"></i>
                            <div>
                                <strong>Materiale su richiesta</strong>
                                <span>Spiegazioni ed esercizi preparati per esigenze specifiche</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section">
            <div class="container">
                <p class="home-section__eyebrow">Come posso aiutarti</p>
                <h2>Dalla spiegazione alla soluzione, senza passaggi lasciati al caso.</h2>
                <p class="home-section__intro">
                    Le attività sono organizzate per trasformare dubbi e lacune in un metodo di lavoro riutilizzabile,
                    anche quando cambiano gli argomenti.
                </p>

                <div class="row g-4">
                    <div class="col-md-4">
                        <x-service-card icon="bi-easel2" title="Lezioni online"
                            description="Sessioni individuali dedicate agli argomenti che richiedono una spiegazione chiara e mirata." />
                    </div>
                    <div class="col-md-4">
                        <x-service-card icon="bi-terminal" title="Esercizi guidati"
                            description="Analisi del problema, scelta della strategia e sviluppo della soluzione passo dopo passo." />
                    </div>
                    <div class="col-md-4">
                        <x-service-card icon="bi-compass" title="Metodo di studio"
                            description="Strumenti per organizzare il ragionamento, verificare gli errori e proseguire in autonomia." />
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section home-section--dark">
            <div class="container">
                <div class="row g-5 align-items-start">
                    <div class="col-lg-7">
                        <p class="home-section__eyebrow">Il metodo</p>
                        <p class="home-method__statement">
                            L’obiettivo non è memorizzare una soluzione, ma capire perché funziona e saperla ricostruire.
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <div class="home-method__steps">
                            <div class="home-method__step">
                                <span>01</span>
                                <div>
                                    <h3>Individuare il punto critico</h3>
                                    <p>Partiamo da ciò che blocca davvero la comprensione.</p>
                                </div>
                            </div>
                            <div class="home-method__step">
                                <span>02</span>
                                <div>
                                    <h3>Costruire il ragionamento</h3>
                                    <p>Ogni passaggio viene motivato e collegato ai concetti fondamentali.</p>
                                </div>
                            </div>
                            <div class="home-method__step">
                                <span>03</span>
                                <div>
                                    <h3>Verificare l’autonomia</h3>
                                    <p>Il metodo viene applicato a un nuovo problema, senza ripetere meccanicamente.</p>
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
                        <p class="home-section__eyebrow">Tariffe</p>
                        <h2>Un riferimento semplice, prima di iniziare.</h2>
                    </div>
                    <div class="col-lg-4">
                        <p class="home-section__intro mb-0">
                            Il percorso viene concordato in base al livello, agli argomenti e al risultato da raggiungere.
                        </p>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-6">
                        <x-price-card title="Scuole superiori" price="15 €" unit="all’ora"
                            description="Recupero, preparazione alle verifiche ed esercitazioni guidate." />
                    </div>
                    <div class="col-lg-6">
                        <x-price-card title="Università" price="20 €" unit="all’ora"
                            description="Preparazione agli esami, programmazione e materie informatiche." />
                    </div>
                </div>
            </div>
        </section>

        <section class="home-section">
            <div class="container">
                <div class="home-profile">
                    @if ($admin?->photo_path)
                        <img class="home-profile__photo" src="{{ $admin->photo_path }}" alt="Tutor di informatica">
                    @endif

                    <div>
                        <p class="home-section__eyebrow">Chi sono</p>
                        <h2>Competenza tecnica e attenzione al modo in cui impari.</h2>
                        <p class="home-section__intro mb-0">
                            Sono laureato magistrale in Ingegneria Informatica e affianco studenti delle scuole superiori
                            e universitari con un approccio orientato alla comprensione reale. Le lezioni partono dalle
                            difficoltà concrete e costruiscono un metodo che resta utile anche dopo l’esame o la verifica.
                        </p>
                        <div class="home-profile__credential">
                            <i class="bi bi-patch-check" aria-hidden="true"></i>
                            <div>
                                <strong>Laurea Magistrale in Ingegneria Informatica</strong>
                                <span>Conseguita con votazione 110/110</span>
                            </div>
                        </div>
                        <a class="btn btn-outline-primary mt-4" href="{{ route('about') }}">
                            Scopri di più
                        </a>
                    </div>
                </div>
            </div>
        </section>

        @if ($reviews->isNotEmpty())
            <section class="home-section home-section--muted">
                <div class="container">
                    <p class="home-section__eyebrow">Recensioni</p>
                    <h2>L’esperienza degli studenti.</h2>

                    @if ($averageRating)
                        <div class="home-reviews__summary">
                            <span class="home-reviews__score">{{ number_format($averageRating, 1) }}</span>
                            <div>
                                <div class="home-reviews__stars" aria-label="Valutazione media: {{ $averageRating }} su 5">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <i class="bi {{ $star <= round($averageRating) ? 'bi-star-fill' : 'bi-star' }}"
                                            aria-hidden="true"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">Valutazione media</small>
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
                <h2>Partiamo dal punto che oggi ti crea più difficoltà.</h2>
                <p>
                    Puoi consultare il materiale disponibile, richiedere una lezione preparata su un argomento specifico
                    oppure contattarmi direttamente per definire il percorso più adatto.
                </p>
                <div class="home-contact__actions">
                    <a class="btn btn-light" href="https://api.whatsapp.com/send?phone=3272991334">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i>
                        Scrivimi su WhatsApp
                    </a>
                    <a class="btn btn-outline-light" href="mailto:marchese89@hotmail.com">
                        <i class="bi bi-envelope" aria-hidden="true"></i>
                        Invia un’email
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection
