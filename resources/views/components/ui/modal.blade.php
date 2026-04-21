{{--
    Modal con Alpine.js.
    Requiere variable Alpine 'open' en el scope padre, o usa prop 'model' para x-model.

    Uso básico:
      <div x-data="{ showModal: false }">
          <x-ui.button @click="showModal = true">Abrir</x-ui.button>
          <x-ui.modal x-model="showModal" title="Confirmar">
              ...
          </x-ui.modal>
      </div>
--}}
@props([
    'title'   => '',
    'size'    => 'md',   // sm | md | lg | xl
    'danger'  => false,
])

@php
    $widths = ['sm' => 'max-w-sm', 'md' => 'max-w-lg', 'lg' => 'max-w-2xl', 'xl' => 'max-w-4xl'];
    $w = $widths[$size] ?? 'max-w-lg';
@endphp

<div
    x-show="$data[Object.keys($data).find(k => k.toLowerCase().includes('show') || k.toLowerCase().includes('open') || k.toLowerCase().includes('modal'))]"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    role="dialog"
    aria-modal="true"
    @if($title) aria-labelledby="modal-title" @endif
    @keydown.escape.window="
        let key = Object.keys($data).find(k => k.toLowerCase().includes('show') || k.toLowerCase().includes('open') || k.toLowerCase().includes('modal'));
        if(key) $data[key] = false;
    "
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    {{-- Backdrop --}}
    <div
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        @click="
            let key = Object.keys($data).find(k => k.toLowerCase().includes('show') || k.toLowerCase().includes('open') || k.toLowerCase().includes('modal'));
            if(key) $data[key] = false;
        "
        aria-hidden="true"
    ></div>

    {{-- Panel --}}
    <div
        class="relative w-full {{ $w }} bg-white rounded-xl shadow-modal flex flex-col max-h-[90vh] overflow-hidden animate-fade-in"
        @click.stop
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 {{ $danger ? 'bg-danger-50' : '' }}">
            <h2 id="modal-title" class="text-base font-semibold {{ $danger ? 'text-danger-700' : 'text-neutral-800' }}">
                {{ $title }}
            </h2>
            <button
                type="button"
                class="btn-icon"
                @click="
                    let key = Object.keys($data).find(k => k.toLowerCase().includes('show') || k.toLowerCase().includes('open') || k.toLowerCase().includes('modal'));
                    if(key) $data[key] = false;
                "
                aria-label="Cerrar"
            >
                <x-icons.x-mark class="w-5 h-5" />
            </button>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5 overflow-y-auto flex-1">
            {{ $slot }}
        </div>

        {{-- Footer (slot opcional) --}}
        @isset($footer)
            <div class="px-6 py-4 border-t border-neutral-100 bg-neutral-50 flex items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
