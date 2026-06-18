@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.subjects_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-7">
                <x-ui.form-card
                    :title="__('admin.teaching.new_subject')"
                    :description="__('admin.teaching.new_subject_description')"
                    icon="bi-book">
                    <form method="POST"
                        action="{{ route('admin.subjects.store') }}">
                        @csrf

                        <x-ui.form-select
                            name="theme_area_id"
                            :label="__('admin.teaching.theme_area')"
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
                            :label="__('admin.teaching.subject_name')"
                            maxlength="255"
                            :value="old('name')" />

                        <x-ui.primary-button type="submit">
                            {{ __('admin.teaching.add_subject') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>

        <x-ui.table-card :title="__('admin.teaching.inserted_subjects')">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">{{ __('admin.teaching.theme_area') }}</th>
                        <th scope="col">{{ __('admin.teaching.name') }}</th>
                        <th scope="col" class="w-50">{{ __('admin.teaching.edit') }}</th>
                        <th scope="col">{{ __('ui.table.actions') }}</th>
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
                                        aria-label="{{ __('admin.teaching.edit_named', ['name' => $subject->name]) }}">

                                    <button type="submit"
                                        class="btn btn-primary btn-sm">
                                        {{ __('admin.actions.save') }}
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
                                            {{ __('admin.actions.delete') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">
                                        {{ trans_choice('admin.teaching.course_count', $subject->courses_count, ['count' => $subject->courses_count]) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                {{ __('admin.teaching.subject_empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <x-ui.pagination :paginator="$subjects" />
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
