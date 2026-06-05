@extends('layouts.layout-bootstrap')

@section('content')

    <style>
        .hero {
            padding: 80px 20px;
            text-align: center;
            background: linear-gradient(135deg, #0d6efd10, #00000005);
            border-radius: 16px;
            margin-bottom: 40px;
        }

        .soft-card {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            transition: .2s;
        }

        .soft-card:hover {
            transform: translateY(10px);
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.12);
            cursor: pointer;
        }
    </style>

    <div class="container py-5">

        <x-hero title="Lezioni private di Ingegneria Informatica" subtitle="Metodo pratico per superare esami e verifiche"
            ctaText="Contattami su WhatsApp" ctaLink="https://api.whatsapp.com/send?phone=3272991334" />

        {{-- Profile --}}
        <div class="row align-items-center mb-5">
            <div class="col-md-5">
                <img src="{{ $admin->photo_path }}" class="img-fluid rounded-3 shadow-sm">
            </div>

            <div class="col-md-7">
                <x-section-title>Chi sono</x-section-title>

                <p>
                    Laurea Magistrale 110/110 in Ingegneria Informatica.
                    Approccio orientato alla comprensione reale, non alla memorizzazione.
                </p>
            </div>
        </div>

        {{-- Services --}}
        <x-section-title>Servizi</x-section-title>

        <div class="row mb-5">
            <div class="col-md-4">
                <x-service-card title="Esercizi guidati" description="Metodo passo-passo" />
            </div>
            <div class="col-md-4">
                <x-service-card title="Lezioni online" description="Sessioni individuali" />
            </div>
            <div class="col-md-4">
                <x-service-card title="Metodo di studio" description="Autonomia dello studente" />
            </div>
        </div>

        {{-- Pricing --}}
        <x-section-title>Tariffe</x-section-title>

        <div class="row mb-5 justify-content-center">
            <div class="col-md-4">
                <x-price-card title="Superiori" price="15€" unit="all’ora" />
            </div>

            <div class="col-md-4">
                <x-price-card title="Università" price="20€" unit="all’ora" />
            </div>
        </div>

        {{-- Contacts --}}
        <div class="card soft-card p-4 text-center mb-5">
            <h4>Contatti</h4>

            <p>
                Email: <a href="mailto:marchese89@hotmail.com">marchese89@hotmail.com</a>
            </p>

            <p>
                Telefono: <a href="tel:+393272991334">+39 327 299 1334</a>
            </p>

            <a class="btn btn-success" href="https://api.whatsapp.com/send?phone=3272991334">
                Scrivimi su WhatsApp
            </a>
        </div>

        {{-- Reviews --}}
        @if ($feedbacks->count())
            <x-section-title>Recensioni</x-section-title>

            <div class="text-center mb-4">
                <h4>{{ number_format($avg, 2) }}/5</h4>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-8">

                    @foreach ($feedbacks as $feed)
                        @if ($feed->review)
                            <x-testimonial-card :name="$feed->student->user->name . ' ' . $feed->student->user->surname" :text="$feed->review" />
                        @endif
                    @endforeach

                </div>
            </div>
        @endif

    </div>

@endsection
