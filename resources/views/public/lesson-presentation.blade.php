@extends('layouts.theme-areas-layout')

@section('page-title')
    <div class="container py-4">
        <h3 class="fw-bold">
            Anteprima Lezione
        </h3>

    </div>
@endsection

@section('inner')
    <div class="container py-4">

        <div class="mx-auto" style="max-width: 1200px;">

            <div class="bg-white shadow rounded-4 p-4 p-lg-5 border">

                <div class="text-center mb-5">

                    <span class="badge bg-primary px-3 py-2 mb-3">
                        Anteprima Lezione
                    </span>

                    <h3 class="mb-2">
                        <strong>Corso:</strong> {{ $course->name }}
                    </h3>

                    <p class="text-muted mb-0">
                        Visualizzazione introduttiva della lezione
                    </p>
                </div>

                <div class="bg-light rounded-4 p-4 mb-4 border">

                    <h5 class="text-uppercase text-muted mb-2" style="letter-spacing: 1px;">
                        Titolo Lezione
                    </h5>

                    <h4 class="fw-semibold mb-0">
                        {{ $lesson->title }}
                    </h4>

                </div>

                <div class="mb-4">

                    <h4 class="fw-bold mb-3">
                        Presentazione
                    </h4>

                    <div class="rounded-4 overflow-hidden shadow-sm border bg-dark">

                        <iframe width="100%" height="800px" src="/protected-files/{{ $lesson->presentation_file }}#view=FitH"
                            style="border: none;">
                        </iframe>

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection
