@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.theme_areas_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row justify-content-center mb-4">
            <div class="col-lg-7">
                <x-ui.form-card
                    :title="__('admin.teaching.new_theme_area')"
                    :description="__('admin.teaching.new_theme_area_description')"
                    icon="bi-collection">
                    <form method="POST"
                        action="{{ route('admin.theme-areas.store') }}">
                        @csrf

                        <x-ui.form-field
                            name="name"
                            :label="__('admin.teaching.theme_area_name')"
                            maxlength="255"
                            :value="old('name')" />

                        <x-ui.primary-button type="submit">
                            {{ __('admin.teaching.add_theme_area') }}
                        </x-ui.primary-button>
                    </form>
                </x-ui.form-card>
            </div>
        </div>

        <x-ui.table-card :title="__('admin.teaching.inserted_theme_areas')">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">{{ __('admin.teaching.name') }}</th>
                        <th scope="col" class="w-50">{{ __('admin.teaching.edit') }}</th>
                        <th scope="col">{{ __('ui.table.actions') }}</th>
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
                                        aria-label="{{ __('admin.teaching.edit_named', ['name' => $themeArea->name]) }}">

                                    <button type="submit"
                                        class="btn btn-primary btn-sm">
                                        {{ __('admin.actions.save') }}
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
                                            {{ __('admin.actions.delete') }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">
                                        {{ trans_choice('admin.teaching.subject_count', $themeArea->subjects_count, ['count' => $themeArea->subjects_count]) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                {{ __('admin.teaching.theme_area_empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <x-ui.pagination :paginator="$themeAreas" />
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
