@extends('layouts.admin-dashboard')

@section('page-title')
    <x-ui.section-header :title="'Nuova Lezione'" />
@endsection

@section('inner')
    @php
        $id = $id ?? request('id');
    @endphp

    <div class="container text-center" style="width: 40%">

        {{-- PRESENTAZIONE --}}
        <h5 class="mt-4">Presentazione</h5>

        <iframe width="90%" height="400"
            src="{{ session()->has('uploaded_pres_lez') ? route('protected-files.show', ['path' => session('uploaded_pres_lez')]) . '#view=FitH' : '' }}">
        </iframe>

        <form method="POST" action="{{ route('admin.lessons.upload-presentation.store') }}" enctype="multipart/form-data" id="upload-pres">
            @csrf
            <input type="hidden" name="id" value="{{ $id }}">

            <input type="file" class="form-control" name="file-pres-lez">

            <div class="progress mt-2 d-none" id="progress-pres">
                <div class="progress-bar" id="percent-pres">0%</div>
            </div>

            <button type="submit" class="btn btn-primary mt-2"
                onclick="upload('upload-pres','file-pres-lez','{{ route('admin.lessons.upload-presentation.store') }}',1)">
                Upload
            </button>
        </form>

        <form method="POST" action="{{ route('admin.lessons.upload-presentation.destroy') }}" class="mt-2">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Cancella file</button>
        </form>

        {{-- SVOLGIMENTO --}}
        @if (session()->has('uploaded_pres_lez'))
            <h5 class="mt-5">Svolgimento</h5>

            <iframe width="90%" height="400"
                src="{{ session()->has('uploaded_lesson') ? route('protected-files.show', ['path' => session('uploaded_lesson')]) . '#view=FitH' : '' }}">
            </iframe>

            <form method="POST" action="{{ route('admin.lessons.upload-file.store') }}" enctype="multipart/form-data" id="upload-lesson">
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">

                <input type="file" class="form-control" name="file-lesson">

                <div class="progress mt-2 d-none" id="progress-lesson">
                    <div class="progress-bar" id="percent-lesson">0%</div>
                </div>

                <button type="submit" class="btn btn-primary mt-2"
                    onclick="upload('upload-lesson','file-lesson','{{ route('admin.lessons.upload-file.store') }}',2)">
                    Upload
                </button>
            </form>

            <form method="POST" action="{{ route('admin.lessons.upload-file.destroy') }}" class="mt-2">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Cancella file</button>
            </form>
        @endif


        {{-- SALVATAGGIO LEZIONE --}}
        @if (session()->has('uploaded_pres_lez') && session()->has('uploaded_lesson'))
            <form method="POST" action="{{ route('admin.lessons.store') }}" class="mt-4">
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">

                <input type="text" class="form-control mt-2" name="numero" placeholder="Numero">
                <input type="text" class="form-control mt-2" name="titolo" placeholder="Titolo">
                <input type="text" class="form-control mt-2" name="prezzo" placeholder="Prezzo €">

                <button class="btn btn-primary mt-3">Carica</button>
            </form>
        @endif

    </div>
@endsection
