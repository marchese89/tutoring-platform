@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.students.chats_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.table-card :title="__('admin.students.chat_list_title')">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>{{ __('admin.students.product_type') }}</th>
                        <th>{{ __('ui.table.title') }}</th>
                        <th>{{ __('admin.students.student') }}</th>
                        <th class="text-center">{{ __('ui.table.status') }}</th>
                        <th>{{ __('ui.table.actions') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($chats as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->product_type_label }}</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->student_name }}</td>
                            <td class="text-center">
                                <x-ui.status-dot
                                    :variant="$item->has_unread_admin_message ? 'danger' : 'success'"
                                    :label="$item->has_unread_admin_message ? __('admin.students.unread') : __('admin.students.read')"
                                />
                            </td>
                            <td>
                                <x-ui.primary-button
                                    href="{{ route('admin.chats.show', $item->id) }}"
                                    size="sm"
                                >
                                    {{ __('admin.students.view_chat') }}
                                </x-ui.primary-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                {{ __('admin.students.chat_empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <x-ui.pagination :paginator="$chats" />
        </x-ui.table-card>
    </x-ui.page-section>
@endsection
