@props(['title' => null, 'description' => null])

<div {{ $attributes->merge(['class' => 'space-y-4']) }}>
    @if ($title || $description)
        <div class="border-b border-neutral-100 pb-3">
            @if ($title)
                <h3 class="text-sm font-semibold text-neutral-700">{{ $title }}</h3>
            @endif
            @if ($description)
                <p class="text-xs text-neutral-500 mt-0.5">{{ $description }}</p>
            @endif
        </div>
    @endif
    {{ $slot }}
</div>
