@props([
    'mode'    => 'light', // light | dark
    'tagline' => true,
])

@php
    $titleColor   = $mode === 'dark' ? 'text-white' : 'text-brand-jet';
    $subtitleColor = $mode === 'dark' ? 'text-brand-secondary' : 'text-primary-500';
    $taglineColor  = $mode === 'dark' ? 'text-neutral-400' : 'text-neutral-500';
@endphp

<div>
    <p class="text-base font-bold leading-tight {{ $titleColor }}">
        <span class="{{ $subtitleColor }}">Sistemas</span> Escolares
    </p>
    @if ($tagline)
        <p class="text-2xs {{ $taglineColor }} mt-0.5 leading-tight">
            Soluciones informáticas para escuelas
        </p>
    @endif
</div>
