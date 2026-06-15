@extends('layouts.student-dashboard')

@section('page-title')
    <x-ui.section-header :title="__('student.invoices.single_title')" />
@endsection

@section('inner')
    <x-ui.page-section>
        <x-ui.pdf-document-card :src="route('protected-files.show', ['path' => $invoice->file_path], false)" :title="$title"
            :action-url="$backUrl" :action-label="__('student.invoices.back')" />
    </x-ui.page-section>
@endsection
