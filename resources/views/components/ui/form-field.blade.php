{{--
    Wrapper unificado: label + control (slot) + hint + error.
    Uso:
      <x-ui.form-field label="DNI" :error="$errors->first('dni')" required>
          <x-ui.input wire:model.blur="dni" />
      </x-ui.form-field>
--}}
@props([
    'label'    => null,
    'hint'     => null,
    'error'    => null,
    'required' => false,
    'for'      => null,   // id del control (para el label)
])

<div class="space-y-0.5">
    @if ($label)
        <label @if($for) for="{{ $for }}" @endif
               class="label{{ $required ? ' label-required' : '' }}">
            {{ $label }}
        </label>
    @endif

    <div class="{{ $error ? 'ring-1 ring-danger-400 rounded-lg' : '' }}">
        {{ $slot }}
    </div>

    @if ($hint && !$error)
        <p class="hint">{{ $hint }}</p>
    @endif

    @if ($error)
        <p class="error-msg" role="alert">
            <x-icons.exclamation-triangle class="w-3.5 h-3.5 shrink-0" />
            {{ $error }}
        </p>
    @endif
</div>
