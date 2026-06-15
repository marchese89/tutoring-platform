@extends('layouts.layout-bootstrap')

@section('content')
    <x-ui.page-section class="py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <x-ui.form-card title="Richiesta lezione su commissione"
                    description="Invia il materiale e ricevi una soluzione personalizzata nel tuo profilo studente."
                    icon="bi-file-earmark-text" body-class="p-4 p-lg-5">
                    <div class="mb-5">
                        <p class="text-muted mb-2">
                            Carica una traccia, un esercizio o altro materiale didattico in formato PDF.
                        </p>
                        <p class="text-muted mb-2">
                            Dopo la valutazione potrai consultare il prezzo e scegliere se acquistare la soluzione.
                        </p>
                        <p class="text-muted mb-0">
                            Dopo l'acquisto avrai accesso anche alla chat di supporto dedicata.
                        </p>
                    </div>

                    @if (! $userCanSubmit)
                        <div class="alert alert-warning rounded-3 mb-0" role="alert">
                            <h5 class="fw-semibold mb-2">
                                Accesso studente richiesto
                            </h5>

                            <p class="mb-3">
                                Questa funzionalità è disponibile esclusivamente agli account studente.
                            </p>

                            @if (! $isAuthenticated)
                                <x-ui.primary-button href="{{ route('login', ['back' => 1]) }}">
                                    Accedi
                                </x-ui.primary-button>
                            @endif
                        </div>
                    @elseif (! $uploadedRequestFile)
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <form method="POST" action="{{ route('lesson-requests.files.store') }}"
                                    enctype="multipart/form-data" data-upload-progress-form>
                                    @csrf

                                    <x-ui.form-file label="Seleziona il file" accept="application/pdf"
                                        class="form-control-lg" wrapper-class="mb-4" required />

                                    <x-ui.upload-progress label="Caricamento file" />

                                    <x-ui.primary-button type="submit" class="w-100 justify-content-center">
                                        Carica file
                                    </x-ui.primary-button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="text-center mb-4">
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                File caricato correttamente
                            </span>
                        </div>

                        <div class="mb-4">
                            <x-ui.pdf-viewer :src="$uploadedRequestFileUrl" title="Anteprima richiesta" size="compact" />
                        </div>

                        <div class="d-flex justify-content-end mb-4">
                            <form method="POST" action="{{ route('lesson-requests.files.destroy') }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                                    Elimina file
                                </button>
                            </form>
                        </div>

                        <hr class="my-4">

                        <form method="POST" action="{{ route('lesson-requests.store') }}">
                            @csrf

                            <x-ui.form-field name="title" label="Titolo / descrizione" maxlength="255"
                                :value="old('title')" placeholder="Inserisci una breve descrizione della richiesta" required />

                            <x-ui.primary-button type="submit" class="w-100 justify-content-center">
                                Invia richiesta
                            </x-ui.primary-button>
                        </form>
                    @endif
                </x-ui.form-card>
            </div>
        </div>
    </x-ui.page-section>
@endsection
