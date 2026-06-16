@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.new_course_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-7">
                <x-ui.form-card
                    :title="__('admin.teaching.new_course')"
                    :description="__('admin.teaching.new_course_description')"
                    icon="bi-mortarboard">
                    <form method="POST"
                        action="{{ route('admin.courses.store') }}">
                        @csrf

                        <x-ui.form-select
                            name="subject_id"
                            :label="__('admin.teaching.subject')"
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
                            :label="__('admin.teaching.course_name')"
                            maxlength="255"
                            :value="old('name')" />

                        <x-ui.primary-button type="submit">
                            {{ __('admin.teaching.add_course') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>

        <x-ui.table-card :title="__('admin.teaching.inserted_courses')">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">{{ __('admin.teaching.theme_area') }}</th>
                        <th scope="col">{{ __('admin.teaching.subject') }}</th>
                        <th scope="col">{{ __('admin.teaching.name') }}</th>
                        <th scope="col" class="w-50">{{ __('admin.teaching.edit') }}</th>
                        <th scope="col">{{ __('ui.table.actions') }}</th>
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
                                        aria-label="{{ __('admin.teaching.edit_named', ['name' => $course->name]) }}">

                                    <button type="submit"
                                        class="btn btn-primary btn-sm">
                                        {{ __('admin.actions.save') }}
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
                                            {{ __('admin.actions.delete') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">
                                        {{ trans_choice('admin.teaching.lesson_count', $course->lessons_count, ['count' => $course->lessons_count]) }},
                                        {{ trans_choice('admin.teaching.exercise_count', $course->exercises_count, ['count' => $course->exercises_count]) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                {{ __('admin.teaching.course_empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
