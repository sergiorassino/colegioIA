@props([
    'title'       => '',
    'subtitle'    => null,
    'breadcrumbs' => [],  // [['label' => '…', 'href' => '…'], ...]
    'actions'     => null,
])

<div class="mb-6">
    {{-- Breadcrumbs --}}
    @if (!empty($breadcrumbs))
        <nav aria-label="Ruta de navegación" class="mb-2">
            <ol class="flex flex-wrap items-center gap-1 text-xs text-neutral-500">
                @foreach ($breadcrumbs as $crumb)
                    @if (!$loop->last)
                        <li>
                            <a href="{{ $crumb['href'] ?? '#' }}"
                               class="hover:text-primary-600 transition-colors"
                               wire:navigate>
                                {{ $crumb['label'] }}
                            </a>
                        </li>
                        <li aria-hidden="true">
                            <x-icons.chevron-right class="w-3 h-3" />
                        </li>
                    @else
                        <li class="text-neutral-700 font-medium" aria-current="page">
                            {{ $crumb['label'] }}
                        </li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif

    {{-- Título + Acciones --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div>
            <h1 class="text-xl font-bold text-brand-jet sm:text-2xl">{{ $title }}</h1>
            @if ($subtitle)
                <p class="mt-0.5 text-sm text-neutral-500">{{ $subtitle }}</p>
            @endif
        </div>

        @if ($actions)
            <div class="flex shrink-0 items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
