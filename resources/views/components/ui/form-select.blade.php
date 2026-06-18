@props([
    'name',
    'label',
    'id' => null,
    'errorName' => null,
    'wrapperClass' => 'mb-3',
])

@php
    $fieldId = $id ?? $name;
    $fieldError = $errorName ?? $name;
    $selectClasses = 'form-select rounded-3'
        . ($errors->has($fieldError) ? ' is-invalid' : '');
@endphp

<div class="{{ $wrapperClass }}">
    <label class="form-label fw-semibold" for="{{ $fieldId }}">
        {{ $label }}
    </label>

    <select id="{{ $fieldId }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => $selectClasses]) }}>
        {{ $slot }}
    </select>

    @error($fieldError)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
