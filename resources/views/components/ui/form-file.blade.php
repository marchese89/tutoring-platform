@props([
    'name' => 'file',
    'label',
    'id' => null,
    'errorName' => null,
    'accept' => null,
    'wrapperClass' => 'mb-3',
])

@php
    $fieldId = $id ?? $name;
    $fieldError = $errorName ?? $name;
@endphp

<div class="{{ $wrapperClass }}">
    <label class="form-label fw-semibold" for="{{ $fieldId }}">
        {{ $label }}
    </label>

    <input id="{{ $fieldId }}" name="{{ $name }}" type="file" data-form-file @if ($accept) accept="{{ $accept }}" @endif
        {{ $attributes->class(['form-control', 'rounded-3', 'is-invalid' => $errors->has($fieldError)]) }}>

    @error($fieldError)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
