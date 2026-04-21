@props(['variant' => 'info'])

@php
    [$cls, $icon] = match($variant) {
        'success' => ['alert-success', 'check-circle'],
        'warning' => ['alert-warning', 'exclamation-triangle'],
        'danger'  => ['alert-danger',  'exclamation-triangle'],
        default   => ['alert-info',    'information-circle'],
    };
@endphp

<div role="alert" {{ $attributes->merge(['class' => $cls]) }}>
    <x-dynamic-component :component="'icons.' . $icon" class="w-5 h-5 shrink-0 mt-0.5" />
    <div class="flex-1">{{ $slot }}</div>
</div>
