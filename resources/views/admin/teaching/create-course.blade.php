@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Nuovo Corso'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-7">
                <x-ui.form-card
                    title="Nuovo corso"
                    description="Associa il corso alla materia corrispondente."
                    icon="bi-mortarboard">
                    <form method="POST"
                        action="{{ route('admin.courses.store') }}">
                        @csrf

                        <x-ui.form-select
                            name="subject_id"
                            label="Materia"
                            required>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    @selected(old('subject_id') == $subject->id)>
                                    {{ $subject->themeArea->name }}
                                    - {{ $subject->name }}
                                </option>
                            @endforeach
                        </x-ui.form-select>

                        <x-ui.form-field
                            name="name"
                            label="Nome corso"
                            maxlength="255"
                            :value="old('name')" />

                        <x-ui.primary-button type="submit">
                            Aggiungi corso
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>

        <x-ui.table-card title="Corsi inseriti">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Area tematica</th>
                        <th scope="col">Materia</th>
                        <th scope="col">Nome</th>
                        <th scope="col" class="w-50">Modifica</th>
                        <th scope="col">Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>{{ $course->subject->themeArea->name ?? '-' }}</td>
                            <td>{{ $course->subject->name ?? '-' }}</td>
                            <td>{{ $course->name }}</td>
                            <td>
                                <form method="POST"
                                    action="{{ route(
                                        'admin.courses.update',
                                        $course->id
                                    ) }}"
                                    class="d-flex gap-2">
                                    @csrf
                                    @method('PUT')

                                    <input type="text"
                                        class="form-control form-control-sm"
                                        name="name"
                                        value="{{ $course->name }}"
                                        maxlength="255"
                                        aria-label="Modifica {{ $course->name }}">

                                    <button type="submit"
                                        class="btn btn-primary btn-sm">
                                        Salva
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if (
                                    $course->lessons_count === 0
                                    && $course->exercises_count === 0
                                )
                                    <form method="POST"
                                        action="{{ route(
                                            'admin.courses.destroy',
                                            $course->id
                                        ) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="btn btn-outline-danger btn-sm">
                                            Elimina
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">
                                        {{ $course->lessons_count }} lezioni,
                                        {{ $course->exercises_count }} esercizi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Nessun corso presente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
