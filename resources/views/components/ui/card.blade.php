@props([
    'title'   => null,
    'actions' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'card']) }}>
    @if ($title || $actions)
        <div class="card-header">
            @if ($title)
                <h3 class="text-sm font-semibold text-neutral-700">{{ $title }}</h3>
            @endif
            @if ($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'card-body' : '' }}">
        {{ $slot }}
    </div>
</div>
