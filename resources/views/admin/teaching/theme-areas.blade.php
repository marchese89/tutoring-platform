@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Aree Tematiche'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-7">
                <x-ui.form-card
                    title="Nuova area tematica"
                    description="Aggiungi una nuova categoria per organizzare le materie."
                    icon="bi-collection">
                    <form method="POST"
                        action="{{ route('admin.theme-areas.store') }}">
                        @csrf

                        <x-ui.form-field
                            name="name"
                            label="Nome area tematica"
                            maxlength="255"
                            :value="old('name')" />

                        <x-ui.primary-button type="submit">
                            Aggiungi area
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>

        <x-ui.table-card title="Aree tematiche inserite">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nome</th>
                        <th scope="col" class="w-50">Modifica</th>
                        <th scope="col">Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($themeAreas as $themeArea)
                        <tr>
                            <td>{{ $themeArea->id }}</td>
                            <td>{{ $themeArea->name }}</td>
                            <td>
                                <form method="POST"
                                    action="{{ route(
                                        'admin.theme-areas.update',
                                        $themeArea->id
                                    ) }}"
                                    class="d-flex gap-2">
                                    @csrf
                                    @method('PUT')

                                    <input type="text"
                                        class="form-control form-control-sm"
                                        name="name"
                                        value="{{ $themeArea->name }}"
                                        maxlength="255"
                                        aria-label="Modifica {{ $themeArea->name }}">

                                    <button type="submit"
                                        class="btn btn-primary btn-sm">
                                        Salva
                                    </button>
                                </form>
                            </td>
                            <td>
                                @if ($themeArea->subjects_count === 0)
                                    <form method="POST"
                                        action="{{ route(
                                            'admin.theme-areas.destroy',
                                            $themeArea->id
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
                                        {{ $themeArea->subjects_count }} materie
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Nessuna area tematica presente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
