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
        <x-ui.form-card class="h-100" :title="__('account.credentials.email_title')"
            :description="__('account.credentials.email_description')" icon="bi-envelope">
            <form method="POST" action="{{ $emailAction }}">
                @csrf

                <x-ui.form-field type="email" name="email" :label="__('account.credentials.new_email')" maxlength="255"
                    :value="old('email', $email)" autocomplete="email" required />

                <x-ui.primary-button type="submit">
                    {{ __('account.credentials.update_email') }}
                </x-ui.primary-button>
            </form>
        </x-ui.form-card>
    </div>

    <div class="col-lg-6">
        <x-ui.form-card class="h-100" :title="__('account.credentials.password_title')" :description="$passwordDescription"
            icon="bi-lock">
            <form method="POST" action="{{ $passwordAction }}">
                @csrf

                <x-ui.password-field name="current_password" :label="__('account.credentials.current_password')"
                    autocomplete="current-password" required />

                <x-ui.password-field name="password" :label="__('account.credentials.new_password')"
                    autocomplete="new-password" required />

                <x-ui.password-field name="password_confirmation" :label="__('account.credentials.password_confirmation')"
                    autocomplete="new-password" required />

                <div class="alert alert-light border rounded-4 small mb-4">
                    <div class="fw-semibold mb-2">
                        {{ __('account.credentials.requirements_title') }}
                    </div>

                    <ul class="mb-0 ps-3">
                        <li>{{ __('account.credentials.requirements.length') }}</li>
                        <li>{{ __('account.credentials.requirements.case') }}</li>
                        <li>{{ __('account.credentials.requirements.number') }}</li>
                        <li>{{ __('account.credentials.requirements.symbol') }}</li>
                    </ul>
                </div>

                <x-ui.primary-button type="submit">
                    {{ __('account.credentials.update_password') }}
                </x-ui.primary-button>
            </form>
        </x-ui.form-card>
    </div>
</div>
