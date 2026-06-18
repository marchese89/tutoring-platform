@props([
    'name',
    'label',
    'type' => 'text',
    'id' => null,
    'value' => null,
    'errorName' => null,
    'wrapperClass' => 'mb-3',
])

@php
    $fieldId = $id ?? $name;
    $fieldError = $errorName ?? $name;
    $inputClasses = 'form-control rounded-3' . ($errors->has($fieldError) ? ' is-invalid' : '');
@endphp

<div class="{{ $wrapperClass }}">
    <label class="form-label fw-semibold" for="{{ $fieldId }}">
        {{ $label }}
    </label>

    <input
        id="{{ $fieldId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $value }}"
        {{ $attributes->merge(['class' => $inputClasses]) }}>

    @error($fieldError)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
