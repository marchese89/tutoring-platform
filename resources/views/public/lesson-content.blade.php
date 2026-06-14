@extends('layouts.theme-areas-layout')

@section('page-title')
    <x-ui.page-header>
        Lezione
    </x-ui.page-header>
@endsection

@section('inner')
    <x-ui.page-section class="py-5">

        <x-ui.section-header :title="'Lezione Corso di ' . $course->name" :description="'Contenuto della lezione: ' . $lesson->title" />

        <x-ui.card>
            <div class="mb-4 text-center">
                <span class="badge bg-primary px-3 py-2 fs-6">
                    Lezione
                </span>

                <h3 class="fw-bold mt-3 mb-2">
                    {{ $lesson->title }}
                </h3>

                <p class="text-muted mb-0">
                    Consulta il materiale direttamente online.
                </p>
            </div>

            <x-ui.pdf-viewer :src="'/protected-files/' . $lesson->content_file" :title="$lesson->title" />
        </x-ui.card>
    </x-ui.page-section>
@endsection
