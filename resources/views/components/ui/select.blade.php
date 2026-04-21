@props([
    'label'    => null,
    'hint'     => null,
    'error'    => null,
    'required' => false,
    'id'       => null,
    'name'     => null,
    'options'  => [],       // ['value' => 'label', ...] o colección con 'id'/'name'
    'placeholder' => null,
    'valueKey' => 'id',
    'labelKey' => 'nombre',
])

@php
    $fieldId  = $id ?? $name ?? $attributes->get('wire:model') ?? $attributes->get('wire:model.blur') ?? 'sel-' . uniqid();
    $fieldId  = str_replace(['.', '[', ']'], '-', $fieldId);
    $hasError = (bool) $error;
@endphp

<div>
    @if ($label)
        <label for="{{ $fieldId }}" class="label{{ $required ? ' label-required' : '' }}">
            {{ $label }}
        </label>
    @endif

    <select
        id="{{ $fieldId }}"
        {{ $attributes->merge([
            'class' => 'input ' . ($hasError ? 'input-error' : ''),
            'aria-invalid'      => $hasError ? 'true' : 'false',
            'aria-describedby'  => $hasError ? $fieldId . '-error' : '',
        ]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if ($slot->isNotEmpty())
            {{ $slot }}
        @else
            @foreach ($options as $opt)
                @php
                    $val = is_array($opt) ? ($opt[$valueKey] ?? '') : (is_object($opt) ? $opt->{$valueKey} : $opt);
                    $lbl = is_array($opt) ? ($opt[$labelKey] ?? $val) : (is_object($opt) ? ($opt->{$labelKey} ?? $val) : $opt);
                @endphp
                <option value="{{ $val }}">{{ $lbl }}</option>
            @endforeach
        @endif
    </select>

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
