@props([
    'label'    => null,
    'hint'     => null,
    'error'    => null,
    'required' => false,
    'id'       => null,
    'name'     => null,
    'rows'     => 3,
])

@php
    $fieldId  = $id ?? $name ?? $attributes->get('wire:model') ?? $attributes->get('wire:model.blur') ?? 'ta-' . uniqid();
    $fieldId  = str_replace(['.', '[', ']'], '-', $fieldId);
    $hasError = (bool) $error;
@endphp

<div>
    @if ($label)
        <label for="{{ $fieldId }}" class="label{{ $required ? ' label-required' : '' }}">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $fieldId }}"
        rows="{{ $rows }}"
        {{ $attributes->merge([
            'class' => 'input resize-none ' . ($hasError ? 'input-error' : ''),
            'aria-invalid'     => $hasError ? 'true' : 'false',
            'aria-describedby' => $hasError ? $fieldId . '-error' : '',
        ]) }}
    >{{ $slot }}</textarea>

    @if ($hint && !$hasError)
        <p class="hint">{{ $hint }}</p>
    @endif

    @if ($hasError)
        <p id="{{ $fieldId }}-error" class="error-msg">
            <x-icons.exclamation-triangle class="w-3.5 h-3.5 shrink-0" />
            {{ $error }}
        </p>
    @endif
</div>
