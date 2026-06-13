@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Modifica Corso'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="text-center mb-4">
            <h2 class="h4 fw-bold mb-1">
                {{ $course->name }}
            </h2>

            <p class="text-muted mb-0">
                Gestisci lezioni ed esercizi associati al corso.
            </p>
        </div>

        <div class="mb-4">
            <x-ui.table-card title="Lezioni">
                <x-slot:actions>
                    <x-ui.primary-button size="sm"
                        href="{{ route('admin.lessons.create', $course->id) }}">
                        Nuova lezione
                    </x-ui.primary-button>
                </x-slot:actions>

                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Numero</th>
                            <th scope="col">Titolo</th>
                            <th scope="col">Prezzo</th>
                            <th scope="col">Operazioni</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($lessons as $lesson)
                            <tr>
                                <td>{{ $lesson->number }}</td>
                                <td class="fw-semibold">
                                    {{ $lesson->title }}
                                </td>
                                <td>{{ number_format($lesson->price, 2, ',', '.') }} €</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <x-ui.primary-button size="sm"
                                            href="{{ route('admin.lessons.edit', [
                                                'course' => $course->id,
                                                'lesson' => $lesson->id,
                                            ]) }}">
                                            Modifica
                                        </x-ui.primary-button>

                                        <form method="POST"
                                            action="{{ route('admin.lessons.destroy', $lesson->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                Elimina
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Nessuna lezione presente.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-ui.table-card>
        </div>

        <x-ui.table-card title="Esercizi">
            <x-slot:actions>
                <x-ui.primary-button size="sm"
                    href="{{ route('admin.exercises.create', $course->id) }}">
                    Nuovo esercizio
                </x-ui.primary-button>
            </x-slot:actions>

            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Titolo</th>
                        <th scope="col">Prezzo</th>
                        <th scope="col">Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($exercises as $exercise)
                        <tr>
                            <td>{{ $exercise->id }}</td>
                            <td class="fw-semibold">
                                {{ $exercise->title }}
                            </td>
                            <td>{{ number_format($exercise->price, 2, ',', '.') }} €</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <x-ui.primary-button size="sm"
                                        href="{{ route('admin.exercises.edit', [
                                            'course' => $course->id,
                                            'exercise' => $exercise->id,
                                        ]) }}">
                                        Modifica
                                    </x-ui.primary-button>

                                    <form method="POST"
                                        action="{{ route('admin.exercises.destroy', $exercise->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            Elimina
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Nessun esercizio presente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
