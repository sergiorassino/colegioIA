<div>
    <x-ui.page-header
        title="Matriculación"
        subtitle="Registro de matrículas de alumnos en cursos del año"
        :breadcrumbs="[['label' => 'Inicio', 'href' => route('staff.dashboard')], ['label' => 'Matrículas']]"
    >
        <x-slot:actions>
            @if(!$showForm)
                <x-ui.button wire:click="iniciarFormulario" icon="plus">Matricular alumno/a</x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    @if(session('success')) <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert> @endif
    @if(session('error'))   <x-ui.alert variant="danger"  class="mb-4">{{ session('error') }}</x-ui.alert>   @endif

    {{-- Formulario de matriculación --}}
    @if($showForm)
        <x-ui.card class="mb-6">
            <x-slot:title>Nueva matrícula</x-slot:title>

            <form wire:submit="matricular" class="space-y-5">

                {{-- Búsqueda de alumno --}}
                <div>
                    <label class="label label-required">Alumno/a</label>
                    @if($alumnoNombre)
                        <div class="flex items-center gap-3 rounded-lg bg-brand-surface/50 border border-primary-200 px-4 py-2.5">
                            <x-icons.user class="w-4 h-4 text-primary-500 shrink-0" />
                            <span class="text-sm font-semibold text-primary-800 flex-1">{{ $alumnoNombre }}</span>
                            <button type="button" wire:click="$set('idLegajos', null); $set('alumnoNombre', null)"
                                    class="text-xs text-primary-500 hover:text-primary-700 underline">
                                Cambiar
                            </button>
                        </div>
                    @else
                        <div class="relative">
                            <x-icons.magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" />
                            <input type="text" wire:model.live.debounce.300ms="searchAlumno"
                                   placeholder="Buscar por apellido, nombre o DNI…"
                                   class="input pl-9 @error('idLegajos') input-error @enderror"
                                   autocomplete="off" />
                            @if($this->alumnosBusqueda->count())
                                <div class="absolute z-20 w-full mt-1 bg-white rounded-xl shadow-modal border border-neutral-100 max-h-48 overflow-y-auto">
                                    @foreach($this->alumnosBusqueda as $a)
                                        <button type="button"
                                                wire:click="seleccionarAlumno({{ $a->id }}, '{{ addslashes($a->apellido . ', ' . $a->nombre) }}')"
                                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-brand-surface/30 transition-colors">
                                            <span class="font-medium text-brand-jet">{{ $a->apellido }}, {{ $a->nombre }}</span>
                                            <span class="text-neutral-400 ml-2 text-xs">DNI {{ $a->dni }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                    @error('idLegajos')
                        <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="label label-required">Ciclo lectivo</label>
                        <select wire:model.live="idTerlec" class="input @error('idTerlec') input-error @enderror">
                            <option value="">Seleccione…</option>
                            @foreach($this->terlecs as $t)
                                <option value="{{ $t->id }}">{{ $t->ano }}</option>
                            @endforeach
                        </select>
                        @error('idTerlec') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label label-required">Nivel</label>
                        <select wire:model.live="idNivel" class="input @error('idNivel') input-error @enderror">
                            <option value="">Seleccione…</option>
                            @foreach($this->niveles as $n)
                                <option value="{{ $n->id }}">{{ $n->nivel }}</option>
                            @endforeach
                        </select>
                        @error('idNivel') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label label-required">Curso</label>
                        <select wire:model.blur="idCursos" class="input @error('idCursos') input-error @enderror"
                                @disabled(!$idNivel || !$idTerlec)>
                            <option value="">Seleccione un curso…</option>
                            @foreach($this->cursosPorNivel as $c)
                                <option value="{{ $c->Id }}">{{ $c->cursec }} {{ $c->turno }}</option>
                            @endforeach
                        </select>
                        @error('idCursos') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label label-required">Condición</label>
                        <select wire:model.blur="idCondiciones" class="input @error('idCondiciones') input-error @enderror">
                            <option value="">Seleccione…</option>
                            @foreach($this->condiciones as $c)
                                <option value="{{ $c->id }}">{{ $c->condicion }}</option>
                            @endforeach
                        </select>
                        @error('idCondiciones') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">Fecha de matrícula</label>
                        <input type="date" wire:model.blur="fechaMatricula" class="input" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-100">
                    <x-ui.button type="button" variant="ghost" wire:click="cancelar">Cancelar</x-ui.button>
                    <x-ui.button type="submit" variant="primary" icon="check">Matricular</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    @endif

    {{-- Filtros --}}
    <div class="mb-5 flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[180px] relative">
            <x-icons.magnifying-glass class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" />
            <input type="text" wire:model.live.debounce.350ms="search"
                   placeholder="Buscar alumno/a…"
                   class="input pl-9" />
        </div>
        <select wire:model.live="filtroTerlec" class="input w-auto text-sm">
            <option value="">Todos los ciclos</option>
            @foreach($this->terlecs as $t)
                <option value="{{ $t->id }}">{{ $t->ano }}</option>
            @endforeach
        </select>
        <select wire:model.live="filtroNivel" class="input w-auto text-sm">
            <option value="">Todos los niveles</option>
            @foreach($this->niveles as $n)
                <option value="{{ $n->id }}">{{ $n->nivel }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tabla --}}
    <x-ui.table>
        <thead>
            <tr>
                <x-ui.th>Alumno/a</x-ui.th>
                <x-ui.th class="hidden sm:table-cell">Curso</x-ui.th>
                <x-ui.th class="hidden md:table-cell">Ciclo</x-ui.th>
                <x-ui.th class="hidden md:table-cell">Condición</x-ui.th>
                <x-ui.th right>Acciones</x-ui.th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100">
            @forelse($matriculas as $mat)
                <tr class="tr-hover">
                    <x-ui.td>
                        <p class="font-medium text-brand-jet">{{ $mat->legajo?->apellido }}, {{ $mat->legajo?->nombre }}</p>
                        <p class="text-2xs text-neutral-400">DNI {{ $mat->legajo?->dni }}</p>
                    </x-ui.td>
                    <x-ui.td class="hidden sm:table-cell">{{ $mat->curso?->cursec }}</x-ui.td>
                    <x-ui.td class="hidden md:table-cell"><x-ui.badge variant="neutral">{{ $mat->terlec?->ano }}</x-ui.badge></x-ui.td>
                    <x-ui.td class="hidden md:table-cell">{{ $mat->condicion?->condicion }}</x-ui.td>
                    <x-ui.td right>
                        @if($confirmDeleteId == $mat->id)
                            <div class="flex items-center justify-end gap-2">
                                <span class="text-xs text-danger-600 font-medium">¿Cancelar matrícula?</span>
                                <x-ui.button size="sm" variant="danger" wire:click="borrar">Sí</x-ui.button>
                                <x-ui.button size="sm" variant="ghost" wire:click="cancelar">No</x-ui.button>
                            </div>
                        @else
                            <button class="btn-icon-danger" wire:click="confirmarBorrar({{ $mat->id }})" title="Cancelar matrícula">
                                <x-icons.trash class="w-4 h-4" />
                            </button>
                        @endif
                    </x-ui.td>
                </tr>
            @empty
                <tr><td colspan="5" class="p-0">
                    <x-ui.empty-state title="Sin matrículas" message="No se encontraron matrículas para los filtros seleccionados." icon="document-text" />
                </td></tr>
            @endforelse
        </tbody>
    </x-ui.table>

    @if($matriculas->hasPages())
        <div class="mt-2">
            <x-ui.pagination :paginator="$matriculas" />
        </div>
    @endif
</div>
