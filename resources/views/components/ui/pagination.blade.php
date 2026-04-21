@props(['paginator'])

@if ($paginator->hasPages())
    <nav aria-label="Paginación" class="flex items-center justify-between gap-4 px-1 py-3">
        <p class="text-xs text-neutral-500">
            Mostrando
            <span class="font-medium">{{ $paginator->firstItem() }}</span>–<span class="font-medium">{{ $paginator->lastItem() }}</span>
            de <span class="font-medium">{{ $paginator->total() }}</span> registros
        </p>

        <div class="flex items-center gap-1">
            @if ($paginator->onFirstPage())
                <span class="btn btn-ghost btn-sm opacity-40 cursor-not-allowed" aria-disabled="true">‹</span>
            @else
                <button class="btn btn-ghost btn-sm" wire:click="previousPage" aria-label="Página anterior">‹</button>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
                @if ($page === $paginator->currentPage())
                    <span class="btn btn-primary btn-sm" aria-current="page">{{ $page }}</span>
                @else
                    <button class="btn btn-ghost btn-sm" wire:click="gotoPage({{ $page }})" aria-label="Ir a página {{ $page }}">
                        {{ $page }}
                    </button>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <button class="btn btn-ghost btn-sm" wire:click="nextPage" aria-label="Página siguiente">›</button>
            @else
                <span class="btn btn-ghost btn-sm opacity-40 cursor-not-allowed" aria-disabled="true">›</span>
            @endif
        </div>
    </nav>
@endif
