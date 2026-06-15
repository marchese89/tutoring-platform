@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.courses.title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.table-card>
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('student.courses.theme_area') }}</th>
                        <th>{{ __('student.courses.subject') }}</th>
                        <th>{{ __('student.courses.course') }}</th>
                        <th>{{ __('ui.table.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($courses as $course)
                        <tr>
                            <th scope="row">{{ $course->id }}</th>

                            <td>{{ $course->subject->themeArea->name }}</td>

                            <td>{{ $course->subject->name }}</td>

                            <td>{{ $course->name }}</td>

                            <td>
                                <x-ui.primary-button href="{{ route('student.courses.show', $course->id) }}">
                                    {{ __('ui.table.view') }}
                                </x-ui.primary-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
