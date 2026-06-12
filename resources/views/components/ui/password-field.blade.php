@props(['name', 'label', 'id' => null, 'errorName' => null, 'wrapperClass' => 'mb-4'])

@php
    $fieldId = $id ?? $name;
    $fieldError = $errorName ?? $name;
@endphp

<div class="{{ $wrapperClass }}">
    <label class="form-label fw-semibold" for="{{ $fieldId }}">
        {{ $label }}
    </label>

    <div class="input-group has-validation">
        <input id="{{ $fieldId }}" name="{{ $name }}" type="password"
            {{ $attributes->class(['form-control', 'is-invalid' => $errors->has($fieldError)]) }}>

        <button class="btn btn-outline-secondary" type="button" data-password-toggle="{{ $fieldId }}"
            aria-controls="{{ $fieldId }}" aria-pressed="false" aria-label="Mostra password">
            <i class="bi bi-eye"></i>
        </button>

        @error($fieldError)
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('click', (event) => {
                const button = event.target.closest('[data-password-toggle]');

                if (!button) {
                    return;
                }

                const field = document.getElementById(button.dataset.passwordToggle);
                const icon = button.querySelector('i');

                if (!field || !icon) {
                    return;
                }

                const showPassword = field.type === 'password';

                field.type = showPassword ? 'text' : 'password';
                icon.className = showPassword ? 'bi bi-eye-slash' : 'bi bi-eye';
                button.setAttribute('aria-pressed', String(showPassword));
                button.setAttribute(
                    'aria-label',
                    showPassword ? 'Nascondi password' : 'Mostra password'
                );
            });
        </script>
    @endpush
@endonce
