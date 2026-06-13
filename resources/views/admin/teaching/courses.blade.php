@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Elenco Corsi'" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.table-card title="Corsi disponibili">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Area tematica</th>
                        <th scope="col">Materia</th>
                        <th scope="col">Corso</th>
                        <th scope="col">Operazioni</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>
                                {{ $course->subject->themeArea->name ?? '-' }}
                            </td>
                            <td>{{ $course->subject->name ?? '-' }}</td>
                            <td class="fw-semibold">{{ $course->name }}</td>
                            <td>
                                <x-ui.primary-button size="sm"
                                    href="{{ route('admin.courses.edit', $course->id) }}">
                                    Gestisci
                                </x-ui.primary-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Nessun corso presente.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
