@props([
    'email',
    'emailAction',
    'passwordAction',
    'passwordDescription',
])

@if (session()->has('success'))
    <div class="alert alert-success rounded-3 mb-4" role="status">
        {{ session()->get('success') }}
    </div>
@endif

<div class="row g-4" data-credentials-settings>
    <div class="col-lg-6">
        <x-ui.form-card class="h-100" title="Modifica email"
            description="Aggiorna l'indirizzo email usato per accedere." icon="bi-envelope">
            <form method="POST" action="{{ $emailAction }}">
                @csrf

                <x-ui.form-field type="email" name="email" label="Nuova email" maxlength="255"
                    :value="old('email', $email)" autocomplete="email" required />

                <x-ui.primary-button type="submit">
                    Aggiorna email
                </x-ui.primary-button>
            </form>
        </x-ui.form-card>
    </div>

    <div class="col-lg-6">
        <x-ui.form-card class="h-100" title="Modifica password" :description="$passwordDescription" icon="bi-lock">
            <form method="POST" action="{{ $passwordAction }}">
                @csrf

                <x-ui.password-field name="current_password" label="Password attuale"
                    autocomplete="current-password" required />

                <x-ui.password-field name="password" label="Nuova password" autocomplete="new-password" required />

                <x-ui.password-field name="password_confirmation" label="Conferma password"
                    autocomplete="new-password" required />

                <div class="alert alert-light border rounded-4 small mb-4">
                    <div class="fw-semibold mb-2">
                        Requisiti password
                    </div>

                    <ul class="mb-0 ps-3">
                        <li>Minimo 10 caratteri</li>
                        <li>Almeno una lettera maiuscola e una minuscola</li>
                        <li>Almeno un numero</li>
                        <li>Almeno un carattere speciale</li>
                    </ul>
                </div>

                <x-ui.primary-button type="submit">
                    Aggiorna password
                </x-ui.primary-button>
            </form>
        </x-ui.form-card>
    </div>
</div>
