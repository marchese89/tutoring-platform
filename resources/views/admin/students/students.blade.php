@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('admin.students.title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <div class="row g-4">
            <x-ui.card-item
                :title="__('admin.students.requests_title')"
                :text="__('admin.students.requests_text')"
                :url="route('admin.lesson-requests.index')"
                column-class="col-lg-5"
            />

            <x-ui.card-item
                :title="__('admin.students.chats_title')"
                :text="__('admin.students.chats_text')"
                :url="route('admin.chats.index')"
                column-class="col-lg-5"
            />
        </div>
    </x-ui.page-section>
@endsection
