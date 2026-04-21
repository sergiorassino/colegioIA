@props([
    'title'   => 'Sin resultados',
    'message' => 'No hay elementos para mostrar.',
    'icon'    => 'document-text',
    'action'  => null,
])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="w-14 h-14 rounded-full bg-brand-surface flex items-center justify-center mb-4">
        <x-dynamic-component :component="'icons.' . $icon" class="w-7 h-7 text-primary-400" />
    </div>
    <h3 class="text-sm font-semibold text-neutral-700 mb-1">{{ $title }}</h3>
    <p class="text-xs text-neutral-500 max-w-xs">{{ $message }}</p>
    @if ($action)
        <div class="mt-4">{{ $action }}</div>
    @endif
</div>
