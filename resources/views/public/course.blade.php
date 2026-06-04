@extends('layouts.theme-areas-layout')

@section('page-title')
    <x-ui.page-header>
        {{ $course->name }}
    </x-ui.page-header>
@endsection

@section('inner')
    <div class="container pb-5">

        @guest
            <div class="alert alert-warning rounded-4 border-0 shadow-sm">
                Devi effettuare l'accesso come studente per acquistare contenuti.
            </div>
        @endguest

        {{-- LEZIONI --}}
        <div class="mb-5">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold mb-0">Lezioni</h3>
                <span class="text-muted">{{ $lessons->count() }} contenuti</span>
            </div>

            <div class="row g-4">

                @foreach ($lessons as $item)
                    <div class="col-xl-4 col-md-6">

                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body d-flex flex-column p-4">

                                <div class="mb-3">
                                    <span class="badge bg-dark">
                                        Lezione {{ $item->number }}
                                    </span>
                                </div>

                                <h5 class="fw-bold">
                                    {{ $item->title }}
                                </h5>

                                <div class="mt-3 mb-4">

                                    @if ($item->price > 0)
                                        <span class="fs-5 fw-semibold">
                                            {{ $item->price }} €
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            Gratis
                                        </span>
                                    @endif

                                </div>

                                <div class="mt-auto d-grid gap-2">

                                    <a href="{{ route('lessons.presentation', ['course' => $item->course_id, 'lesson' => $item->id]) }}"
                                        class="btn btn-outline-primary rounded-3">
                                        Anteprima
                                    </a>

                                    @if ($item->price == 0 || $item->can_show)
                                        <a href="{{ route('lessons.show', ['course' => $item->course_id, 'lesson' => $item->id]) }}"
                                            class="btn btn-primary rounded-3">
                                            Contenuto
                                        </a>
                                    @elseif(auth()->check() && auth()->user()->role === 'student')
                                        <a href="{{ route('cart.items.store', ['id' => $item->id, 'type' => 0]) }}"
                                            class="btn btn-primary rounded-3">
                                            Acquista
                                        </a>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        {{-- ESERCIZI --}}
        <div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold mb-0">Esercizi</h3>
                <span class="text-muted">{{ $exercises->count() }} contenuti</span>
            </div>

            <div class="row g-4">

                @foreach ($exercises as $item)
                    <div class="col-xl-4 col-md-6">

                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body d-flex flex-column p-4">

                                <h5 class="fw-bold mb-3">
                                    {{ $item->title }}
                                </h5>

                                <div class="mb-4">

                                    @if ($item->price > 0)
                                        <span class="fs-5 fw-semibold">
                                            {{ $item->price }} €
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            Gratis
                                        </span>
                                    @endif

                                </div>

                                <div class="mt-auto d-grid gap-2">

                                    <a href="{{ route('exercises.trace', ['course' => $course->id, 'exercise' => $item->id]) }}"
                                        class="btn btn-outline-primary rounded-3">
                                        Anteprima
                                    </a>

                                    @if ($item->price == 0 || $item->can_show)
                                        <button class="btn btn-primary rounded-3">
                                            Contenuto
                                        </button>
                                    @elseif(auth()->check() && auth()->user()->role === 'student')
                                        <a href="{{ route('cart.items.store', ['id' => $item->id, 'type' => 2]) }}"
                                            class="btn btn-primary rounded-3">
                                            Acquista
                                        </a>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </div>
@endsection
