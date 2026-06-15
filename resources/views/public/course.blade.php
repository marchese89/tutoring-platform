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
                {{ __('public.catalog.student_login_required') }}
            </div>
        @endguest

        {{-- Lessons --}}
        <div class="mb-5">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold mb-0">{{ __('public.catalog.lessons') }}</h3>
                <span class="text-muted">
                    {{ trans_choice('public.catalog.content_count', $lessons->count(), ['count' => $lessons->count()]) }}
                </span>
            </div>

            <div class="row g-4">

                @foreach ($lessons as $item)
                    <div class="col-xl-4 col-md-6">

                        <div class="card border-0 shadow-sm rounded-4 h-100">

                            <div class="card-body d-flex flex-column p-4">

                                <div class="mb-3">
                                    <span class="badge bg-dark">
                                        {{ __('public.catalog.lesson_number', ['number' => $item->number]) }}
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
                                            {{ __('public.catalog.free') }}
                                        </span>
                                    @endif

                                </div>

                                <div class="mt-auto d-grid gap-2">

                                    <a href="{{ route('lessons.presentation', ['course' => $item->course_id, 'lesson' => $item->id]) }}"
                                        class="btn btn-outline-primary rounded-3">
                                        {{ __('public.catalog.preview') }}
                                    </a>

                                    @if ($item->price == 0 || $item->can_show)
                                        <a href="{{ route('lessons.show', ['course' => $item->course_id, 'lesson' => $item->id]) }}"
                                            class="btn btn-primary rounded-3">
                                            {{ __('public.catalog.content') }}
                                        </a>
                                    @elseif(auth()->check() && auth()->user()->role === 'student')
                                        <form method="POST"
                                            action="{{ route('cart.items.store', ['id' => $item->id, 'type' => 0]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary rounded-3 w-100">
                                                {{ __('public.catalog.purchase') }}
                                            </button>
                                        </form>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>

        </div>

        {{-- Exercises --}}
        <div>

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3 class="fw-bold mb-0">{{ __('public.catalog.exercises') }}</h3>
                <span class="text-muted">
                    {{ trans_choice('public.catalog.content_count', $exercises->count(), ['count' => $exercises->count()]) }}
                </span>
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
                                            {{ __('public.catalog.free') }}
                                        </span>
                                    @endif

                                </div>

                                <div class="mt-auto d-grid gap-2">

                                    <a href="{{ route('exercises.trace', ['course' => $course->id, 'exercise' => $item->id]) }}"
                                        class="btn btn-outline-primary rounded-3">
                                        {{ __('public.catalog.preview') }}
                                    </a>

                                    @if ($item->price == 0 || $item->can_show)
                                        <button class="btn btn-primary rounded-3">
                                            {{ __('public.catalog.content') }}
                                        </button>
                                    @elseif(auth()->check() && auth()->user()->role === 'student')
                                        <form method="POST"
                                            action="{{ route('cart.items.store', ['id' => $item->id, 'type' => 2]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary rounded-3 w-100">
                                                {{ __('public.catalog.purchase') }}
                                            </button>
                                        </form>
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
