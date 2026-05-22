@extends('layouts.theme-areas-layout')

@section('page-title')
    <x-ui.page-header>
        Lezione
    </x-ui.page-header>
@endsection

@section('inner')
    <div class="container py-5">

        <x-ui.section-header :title="'Lezione Corso di ' . $corso->name" :description="'Contenuto della lezione: ' . $lezione->title" />

        <div class="row justify-content-center">
            <div class="col-12">

                <x-ui.card>

                    <div class="mb-4 text-center">
                        <span class="badge bg-primary px-3 py-2 fs-6">
                            Lezione
                        </span>

                        <h3 class="fw-bold mt-3 mb-2">
                            {{ $lezione->title }}
                        </h3>

                        <p class="text-muted mb-0">
                            Consulta il materiale direttamente online.
                        </p>
                    </div>

                    <div class="ratio ratio-16x9">
                        <iframe src="/protected_file/{{ $lezione->lesson }}#view=FitH" class="rounded border" allowfullscreen>
                        </iframe>
                    </div>

                </x-ui.card>

            </div>
        </div>

    </div>
@endsection
