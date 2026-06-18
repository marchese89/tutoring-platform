@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.teaching.course_list_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.table-card :title="__('admin.teaching.available_courses')">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">{{ __('admin.teaching.theme_area') }}</th>
                        <th scope="col">{{ __('admin.teaching.subject') }}</th>
                        <th scope="col">{{ __('admin.teaching.course') }}</th>
                        <th scope="col">{{ __('ui.table.actions') }}</th>
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
                                    {{ __('admin.actions.manage') }}
                                </x-ui.primary-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                {{ __('admin.teaching.course_empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <x-ui.pagination :paginator="$courses" />
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
