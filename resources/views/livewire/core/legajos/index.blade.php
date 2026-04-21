<div>
    <x-ui.page-header
        title="Legajos de alumnos"
        subtitle="Registro de datos personales de alumnos"
        :breadcrumbs="[['label' => 'Inicio', 'href' => route('staff.dashboard')], ['label' => 'Legajos']]"
    >
        <x-slot:actions>
            <a href="{{ route('staff.legajos.crear') }}" wire:navigate>
                <x-ui.button icon="plus">Nuevo legajo</x-ui.button>
            </a>
        </x-slot:actions>
    </x-ui.page-header>

    {{-- Filtros --}}
    <div class="mb-5 flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[220px] relative">
            <x-icons.magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" />
            <input type="text"
                   wire:model.live.debounce.350ms="search"
                   placeholder="Buscar por apellido, nombre o DNI…"
                   class="input pl-9" />
        </div>
        <select wire:model.live="filtroNivel" class="input w-auto text-sm">
            <option value="">Todos los niveles</option>
            @foreach($this->niveles as $nivel)
                <option value="{{ $nivel->id }}">{{ $nivel->nivel }}</option>
            @endforeach
        </select>
        <div wire:loading wire:target="search,filtroNivel">
            <svg class="animate-spin w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z"/>
            </svg>
        </div>
    </div>

    {{-- Tabla --}}
    <x-ui.table>
        <thead>
            <tr>
                <x-ui.th>Alumno/a</x-ui.th>
                <x-ui.th class="hidden sm:table-cell">DNI</x-ui.th>
                <x-ui.th class="hidden md:table-cell">Nivel</x-ui.th>
                <x-ui.th right>Acciones</x-ui.th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse($legajos as $legajo)
                <tr class="tr-hover">
                    <x-ui.td>
                        <p class="font-medium text-brand-jet">{{ $legajo->apellido }}, {{ $legajo->nombre }}</p>
                        <p class="text-2xs text-neutral-400 sm:hidden">DNI {{ $legajo->dni }}</p>
                    </x-ui.td>
                    <x-ui.td class="hidden sm:table-cell">{{ $legajo->dni }}</x-ui.td>
                    <x-ui.td class="hidden md:table-cell">
                        @if($legajo->nivel)
                            <x-ui.badge variant="primary">{{ $legajo->nivel->abrev }}</x-ui.badge>
                        @endif
                    </x-ui.td>
                    <x-ui.td right>
                        <a href="{{ route('staff.legajos.editar', $legajo->id) }}" wire:navigate>
                            <x-ui.button size="sm" variant="secondary">Ver / Editar</x-ui.button>
                        </a>
                    </x-ui.td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-0">
                    <x-ui.empty-state
                        title="{{ $search ? 'Sin resultados' : 'Sin legajos' }}"
                        message="{{ $search ? 'No se encontraron legajos para «' . $search . '».' : 'No hay legajos registrados aún.' }}"
                        icon="users"
                    />
                </td></tr>
            @endforelse
        </tbody>
    </x-ui.table>

    {{-- Paginación --}}
    @if($legajos->hasPages())
        <div class="mt-2">
            <x-ui.pagination :paginator="$legajos" />
        </div>
    @endif
</div>
