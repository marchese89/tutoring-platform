@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.edit_course')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="text-center mb-4">
            <h2 class="h4 fw-bold mb-1">
                {{ $course->name }}
            </h2>

            <p class="text-muted mb-0">
                {{ __('admin.teaching.edit_course_description') }}
            </p>
        </div>

        <div class="mb-4">
            <x-ui.table-card :title="__('admin.teaching.lessons')">
                <x-slot:actions>
                    <x-ui.primary-button size="sm"
                        href="{{ route('admin.lessons.create', $course->id) }}">
                        {{ __('admin.teaching.new_lesson') }}
                    </x-ui.primary-button>
                </x-slot:actions>

                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">{{ __('admin.teaching.number') }}</th>
                            <th scope="col">{{ __('ui.table.title') }}</th>
                            <th scope="col">{{ __('ui.table.price') }}</th>
                            <th scope="col">{{ __('ui.table.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($lessons as $lesson)
                            <tr>
                                <td>{{ $lesson->number }}</td>
                                <td class="fw-semibold">
                                    {{ $lesson->title }}
                                </td>
                                <td>{{ \App\Helpers\NumberHelper::format($lesson->price) }} €</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <x-ui.primary-button size="sm"
                                            href="{{ route('admin.lessons.edit', [
                                                'course' => $course->id,
                                                'lesson' => $lesson->id,
                                            ]) }}">
                                            {{ __('admin.actions.edit') }}
                                        </x-ui.primary-button>

                                        <form method="POST"
                                            action="{{ route('admin.lessons.destroy', $lesson->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                {{ __('admin.actions.delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    {{ __('admin.teaching.lesson_empty') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-ui.table-card>
        </div>

        <x-ui.table-card :title="__('admin.teaching.exercises')">
            <x-slot:actions>
                <x-ui.primary-button size="sm"
                    href="{{ route('admin.exercises.create', $course->id) }}">
                    {{ __('admin.teaching.new_exercise') }}
                </x-ui.primary-button>
            </x-slot:actions>

            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">{{ __('ui.table.title') }}</th>
                        <th scope="col">{{ __('ui.table.price') }}</th>
                        <th scope="col">{{ __('ui.table.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($exercises as $exercise)
                        <tr>
                            <td>{{ $exercise->id }}</td>
                            <td class="fw-semibold">
                                {{ $exercise->title }}
                            </td>
                            <td>{{ \App\Helpers\NumberHelper::format($exercise->price) }} €</td>
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    <x-ui.primary-button size="sm"
                                        href="{{ route('admin.exercises.edit', [
                                            'course' => $course->id,
                                            'exercise' => $exercise->id,
                                        ]) }}">
                                        {{ __('admin.actions.edit') }}
                                    </x-ui.primary-button>

                                    <form method="POST"
                                        action="{{ route('admin.exercises.destroy', $exercise->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            {{ __('admin.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                {{ __('admin.teaching.exercise_empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
