@extends('admin.dashboard-admin')

@section('page-title')
    <x-ui.section-header :title="'Modifica Partita IVA'" />
@endsection

@section('inner')
    <div class="container py-4" style="max-width: 400px;">

        <div class="card shadow-sm">

            <div class="card-header">
                <h5 class="mb-0">Modifica Partita IVA</h5>
            </div>

            <div class="card-body">

                <form method="POST" action="mod-piva">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Partita IVA</label>

                        <input type="text" class="form-control" id="piva" name="piva" minlength="11"
                            maxlength="11" value="{{ auth()->user()->admin->piva }}">

                        <script>
                            var piva_ = new LiveValidation('piva', {
                                onlyOnSubmit: true
                            });
                            piva_.add(Validate.Presence);
                            piva_.add(Validate.InteriPositivi);
                        </script>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Salva
                    </button>

                </form>

            </div>
        </div>

    </div>
@endsection
