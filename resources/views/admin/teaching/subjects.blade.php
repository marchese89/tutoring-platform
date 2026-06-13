@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Materie'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-7">
                <x-ui.form-card
                    title="Nuova materia"
                    description="Associa una materia alla relativa area tematica."
                    icon="bi-book">
                    <form method="POST"
                        action="{{ route('admin.subjects.store') }}">
                        @csrf

                        <x-ui.form-select
                            name="theme_area_id"
                            label="Area tematica"
                            required>
                            @foreach ($themeAreas as $themeArea)
                                <option value="{{ $themeArea->id }}"
                                    @selected(
                                        old('theme_area_id') == $themeArea->id
                                    )>
                                    {{ $themeArea->name }}
                                </option>
                            @endforeach
                        </x-ui.form-select>

                        <x-ui.form-field
                            name="name"
                            label="Nome materia"
                            maxlength="255"
                            :value="old('name')" />

                        <x-ui.primary-button type="submit">
                            Aggiungi materia
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>

        <x-ui.table-card title="Materie inserite">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Area tematica</th>
                        <th scope="col">Nome</th>
                        <th scope="col" class="w-50">Modifica</th>
                        <th scope="col">Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($subjects as $subject)
                        <tr>
                            <td>{{ $subject->id }}</td>
                            <td>{{ $subject->themeArea->name ?? '-' }}</td>
                            <td>{{ $subject->name }}</td>
                            <td>
                                <form method="POST"
                                    action="{{ route(
                                        'admin.subjects.update',
                                        $subject->id
                                    ) }}"
                                    class="d-flex gap-2">
                                    @csrf
                                    @method('PUT')

                                    <input type="text"
                                        class="form-control form-control-sm"
                                        name="name"
                                        value="{{ $subject->name }}"
                                        maxlength="255"
                                        aria-label="Modifica {{ $subject->name }}">

                                    <button type="submit"
                                        class="btn btn-primary btn-sm">
                                        Salva
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if ($subject->courses_count === 0)
                                    <form method="POST"
                                        action="{{ route(
                                            'admin.subjects.destroy',
                                            $subject->id
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
                                        {{ $subject->courses_count }} corsi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Nessuna materia presente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
