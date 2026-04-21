<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Legajos de alumnos</h1>
        <a href="{{ route('staff.legajos.crear') }}"
           class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
            + Nuevo legajo
        </a>
    </div>

    {{-- Filtros --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Buscar por apellido, nombre o DNI..."
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
        </div>
        <select wire:model.live="filtroNivel" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Todos los niveles</option>
            @foreach($this->niveles as $nivel)
                <option value="{{ $nivel->id }}">{{ $nivel->nivel }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tabla --}}
    <div class="bg-white rounded-lg shadow ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno/a</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">DNI</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Nivel</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($legajos as $legajo)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $legajo->apellido }}, {{ $legajo->nombre }}</div>
                            <div class="text-xs text-gray-400 sm:hidden">DNI: {{ $legajo->dni }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden sm:table-cell">{{ $legajo->dni }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $legajo->nivel?->abrev }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('staff.legajos.editar', $legajo->id) }}"
                               class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                Ver/Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400">
                            No se encontraron legajos{{ $search ? " para «{$search}»" : '' }}.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        @if($legajos->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $legajos->links() }}
            </div>
        @endif
    </div>
</div>
