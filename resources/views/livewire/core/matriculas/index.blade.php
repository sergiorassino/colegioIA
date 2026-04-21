<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Matriculación</h1>
        @if(!$showForm)
            <button wire:click="iniciarFormulario" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                + Matricular alumno/a
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Formulario de matriculación --}}
    @if($showForm)
        <div class="mb-6 bg-white rounded-lg shadow ring-1 ring-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Nueva matrícula</h2>
            <form wire:submit="matricular" class="space-y-5">

                {{-- Búsqueda de alumno --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alumno/a *</label>
                    @if($alumnoNombre)
                        <div class="flex items-center gap-3 rounded-md bg-indigo-50 border border-indigo-200 px-4 py-2.5">
                            <span class="text-sm font-semibold text-indigo-900">{{ $alumnoNombre }}</span>
                            <button type="button" wire:click="$set('idLegajos', null); $set('alumnoNombre', null)" class="text-xs text-indigo-500 hover:text-indigo-700 ml-auto">Cambiar</button>
                        </div>
                    @else
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="searchAlumno"
                                   placeholder="Buscar por apellido, nombre o DNI..."
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @if($this->alumnosBusqueda->count())
                                <div class="absolute z-20 w-full mt-1 bg-white rounded-md shadow-lg ring-1 ring-gray-200 max-h-48 overflow-y-auto">
                                    @foreach($this->alumnosBusqueda as $a)
                                        <button type="button"
                                                wire:click="seleccionarAlumno({{ $a->id }}, '{{ addslashes($a->apellido . ', ' . $a->nombre) }}')"
                                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-indigo-50">
                                            <span class="font-medium">{{ $a->apellido }}, {{ $a->nombre }}</span>
                                            <span class="text-gray-400 ml-2">DNI {{ $a->dni }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                    @error('idLegajos') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {{-- Ciclo lectivo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ciclo lectivo *</label>
                        <select wire:model.live="idTerlec" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione...</option>
                            @foreach($this->terlecs as $t)
                                <option value="{{ $t->id }}">{{ $t->ano }}</option>
                            @endforeach
                        </select>
                        @error('idTerlec') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nivel --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nivel *</label>
                        <select wire:model.live="idNivel" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione...</option>
                            @foreach($this->niveles as $n)
                                <option value="{{ $n->id }}">{{ $n->nivel }}</option>
                            @endforeach
                        </select>
                        @error('idNivel') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Curso --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Curso *</label>
                        <select wire:model="idCursos" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" @disabled(!$idNivel || !$idTerlec)>
                            <option value="">Seleccione un curso...</option>
                            @foreach($this->cursosPorNivel as $c)
                                <option value="{{ $c->Id }}">{{ $c->cursec }} {{ $c->turno }}</option>
                            @endforeach
                        </select>
                        @error('idCursos') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Condición --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Condición *</label>
                        <select wire:model="idCondiciones" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Seleccione...</option>
                            @foreach($this->condiciones as $c)
                                <option value="{{ $c->id }}">{{ $c->condicion }}</option>
                            @endforeach
                        </select>
                        @error('idCondiciones') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de matrícula</label>
                        <input type="date" wire:model="fechaMatricula"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                    <button type="button" wire:click="cancelar" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500" wire:loading.attr="disabled">
                        <span wire:loading.remove>Matricular</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Filtros listado --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <div class="flex-1 min-w-[180px]">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Buscar alumno..."
                   class="block w-full rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <select wire:model.live="filtroTerlec" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Todos los ciclos</option>
            @foreach($this->terlecs as $t)
                <option value="{{ $t->id }}">{{ $t->ano }}</option>
            @endforeach
        </select>
        <select wire:model.live="filtroNivel" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Todos los niveles</option>
            @foreach($this->niveles as $n)
                <option value="{{ $n->id }}">{{ $n->nivel }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tabla de matrículas --}}
    <div class="bg-white rounded-lg shadow ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alumno/a</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Curso</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Ciclo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Condición</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($matriculas as $mat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium text-gray-900">{{ $mat->legajo?->apellido }}, {{ $mat->legajo?->nombre }}</div>
                            <div class="text-xs text-gray-400">DNI {{ $mat->legajo?->dni }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $mat->curso?->cursec }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $mat->terlec?->ano }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $mat->condicion?->condicion }}</td>
                        <td class="px-4 py-3 text-right space-x-3">
                            @if($confirmDeleteId == $mat->id)
                                <span class="text-xs text-red-600 font-medium">¿Cancelar matrícula?</span>
                                <button wire:click="borrar" class="text-xs text-red-600 hover:text-red-800 font-medium">Sí</button>
                                <button wire:click="cancelar" class="text-xs text-gray-500">No</button>
                            @else
                                <button wire:click="confirmarBorrar({{ $mat->id }})" class="text-xs text-red-500 hover:text-red-700">Cancelar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">No se encontraron matrículas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($matriculas->hasPages())
            <div class="border-t border-gray-200 px-4 py-3">
                {{ $matriculas->links() }}
            </div>
        @endif
    </div>
</div>
