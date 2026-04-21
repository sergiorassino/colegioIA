@props([
    'label'       => null,
    'hint'        => null,
    'error'       => null,
    'required'    => false,
    'id'          => null,
    'name'        => null,
    'type'        => 'text',
])

@php
    $fieldId = $id ?? $name ?? $attributes->get('wire:model') ?? $attributes->get('wire:model.blur') ?? 'field-' . uniqid();
    $fieldId = str_replace(['.', '[', ']'], '-', $fieldId);
    $hasError = $error || $attributes->has('aria-invalid');
@endphp

<div>
    @if ($label)
        <label for="{{ $fieldId }}" class="label{{ $required ? ' label-required' : '' }}">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $fieldId }}"
        {{ $attributes->merge([
            'class' => 'input ' . ($hasError ? 'input-error' : ''),
            'aria-invalid' => $hasError ? 'true' : 'false',
        ]) }}
        @if($hasError) aria-describedby="{{ $fieldId }}-error" @endif
    />

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
