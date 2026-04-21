@props([
    'size' => 'md',   // sm | md | lg
    'mode' => 'dark', // dark (sidebar/color bg) | light (white bg)
])

@php
    $heights = ['sm' => 'h-7', 'md' => 'h-9', 'lg' => 'h-12'];
    $h = $heights[$size] ?? 'h-9';
    $src = $mode === 'dark'
        ? asset('img/logo-se-oficial.png')
        : asset('img/logo-se-light.svg');
@endphp

<img src="{{ $src }}" alt="Sistemas Escolares" class="{{ $h }} w-auto object-contain" />
