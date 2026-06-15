@props(['name', 'label', 'id' => null, 'value' => null, 'errorName' => null, 'wrapperClass' => 'mb-3', 'rows' => 4])

@php
    $fieldId = $id ?? $name;
    $fieldError = $errorName ?? $name;
    $textareaClasses = 'form-control rounded-3' . ($errors->has($fieldError) ? ' is-invalid' : '');
@endphp

<div class="{{ $wrapperClass }}">
    <label class="form-label fw-semibold" for="{{ $fieldId }}">
        {{ $label }}
    </label>

    <textarea id="{{ $fieldId }}" name="{{ $name }}" rows="{{ $rows }}"
        {{ $attributes->merge(['class' => $textareaClasses]) }}>{{ $value }}</textarea>

    @error($fieldError)
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
</div>
