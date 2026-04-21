@props(['items' => []])

@if (!empty($items))
    <nav aria-label="Ruta de navegación">
        <ol class="flex flex-wrap items-center gap-1 text-xs text-neutral-400">
            @foreach ($items as $item)
                @if (!$loop->last)
                    <li>
                        <a href="{{ $item['href'] ?? '#' }}"
                           class="hover:text-brand-white transition-colors"
                           wire:navigate>
                            {{ $item['label'] }}
                        </a>
                    </li>
                    <li aria-hidden="true">
                        <x-icons.chevron-right class="w-3 h-3" />
                    </li>
                @else
                    <li class="text-neutral-200 font-medium" aria-current="page">
                        {{ $item['label'] }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
