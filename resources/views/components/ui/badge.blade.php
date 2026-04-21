@props(['variant' => 'primary'])

@php
    $cls = match($variant) {
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'danger'  => 'badge-danger',
        'neutral' => 'badge-neutral',
        default   => 'badge-primary',
    };
@endphp

<span {{ $attributes->merge(['class' => $cls]) }}>
    {{ $slot }}
</span>
