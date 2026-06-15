@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center g-4">
            @forelse ($certificates as $certificate)
                <div class="col-12 col-xl-10">
                    <x-ui.card>
                        <h2 class="h4 fw-bold text-center mb-4">
                            {{ $certificate->name }}
                        </h2>

                        @if ($certificate->file_path)
                            <x-ui.pdf-viewer :src="$certificate->file_path" :title="'Certificato: ' . $certificate->name"
                                size="compact" />
                        @endif
                    </x-ui.card>
                </div>
            @empty
                <div class="col-lg-8">
                    <x-ui.empty-state title="Nessun certificato disponibile"
                        text="I certificati saranno pubblicati in questa pagina." />
                </div>
            @endforelse
        </div>
    </x-ui.page-section>
@endsection
