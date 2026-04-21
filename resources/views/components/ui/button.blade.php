@props([
    'variant' => 'primary', // primary | secondary | ghost | danger | link
    'size'    => 'md',      // sm | md | lg
    'icon'    => null,      // nombre del componente icon (sin 'icons.')
    'type'    => 'button',
])

@php
    $variantClass = match($variant) {
        'secondary' => 'btn-secondary',
        'ghost'     => 'btn-ghost',
        'danger'    => 'btn-danger',
        'link'      => 'btn-link',
        default     => 'btn-primary',
    };

    $sizeClass = match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => '',
    };
@endphp

<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => trim("$variantClass $sizeClass")]) }}
>
    @if ($icon)
        <x-dynamic-component :component="'icons.' . $icon" class="w-4 h-4 shrink-0" />
    @endif

    <span wire:loading.remove wire:target="{{ $attributes->get('wire:click', '') ?: ($type === 'submit' ? 'guardar save submit' : '') }}">
        {{ $slot }}
    </span>
    <span wire:loading wire:target="{{ $attributes->get('wire:click', '') ?: ($type === 'submit' ? 'guardar save submit' : '') }}" class="inline-flex items-center gap-1">
        <svg class="animate-spin w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z"/>
        </svg>
        Cargando…
    </span>
</button>
