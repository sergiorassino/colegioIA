<div>
    <x-ui.page-header
        title="Cursos del año"
        subtitle="Gestión de cursos y materias del ciclo lectivo"
        :breadcrumbs="[['label' => 'Inicio', 'href' => route('staff.dashboard')], ['label' => 'Cursos del año']]"
    >
        <x-slot:actions>
            @if(!$showCursoForm)
                <x-ui.button wire:click="crearCurso" icon="plus">Nuevo curso</x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    @if(session('success')) <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert> @endif
    @if(session('error'))   <x-ui.alert variant="danger"  class="mb-4">{{ session('error') }}</x-ui.alert>   @endif

    {{-- Filtros --}}
    <div class="mb-5 flex flex-wrap gap-3 items-center">
        <x-icons.funnel class="w-4 h-4 text-neutral-400 shrink-0" />
        <select wire:model.live="filtroTerlec" class="input w-auto text-sm">
            <option value="">Todos los ciclos lectivos</option>
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

    {{-- Formulario de curso --}}
    @if($showCursoForm)
        <x-ui.card class="mb-6">
            <x-slot:title>{{ $editCursoId ? 'Editar curso' : 'Nuevo curso del año' }}</x-slot:title>
            @if(!$editCursoId)
                <x-ui.alert variant="warning" class="mb-4">
                    Al crear un curso se copian automáticamente todas las materias modelo del curso elegido para el ciclo lectivo seleccionado.
                </x-ui.alert>
            @endif
            <form wire:submit="guardarCurso" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="label label-required">Ciclo lectivo</label>
                    <select wire:model.blur="formTerlec" class="input @error('formTerlec') input-error @enderror">
                        <option value="">Seleccione…</option>
                        @foreach($this->terlecs as $t)
                            <option value="{{ $t->id }}">{{ $t->ano }}</option>
                        @endforeach
                    </select>
                    @error('formTerlec') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label label-required">Nivel</label>
                    <select wire:model.live="formNivel" class="input @error('formNivel') input-error @enderror">
                        <option value="">Seleccione…</option>
                        @foreach($this->niveles as $n)
                            <option value="{{ $n->id }}">{{ $n->nivel }}</option>
                        @endforeach
                    </select>
                    @error('formNivel') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label label-required">Curso modelo</label>
                    <select wire:model.blur="formCurPlan" class="input @error('formCurPlan') input-error @enderror" @disabled(!$formNivel)>
                        <option value="">Seleccione un curso modelo…</option>
                        @foreach($this->curplanes as $cp)
                            <option value="{{ $cp->id }}">{{ $cp->curPlanCurso }}</option>
                        @endforeach
                    </select>
                    @error('formCurPlan') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label label-required">Nombre del curso</label>
                    <input type="text" wire:model.blur="formCursec" maxlength="30"
                           class="input @error('formCursec') input-error @enderror" placeholder="Ej: 1er Año A"
                           @error('formCursec') aria-invalid="true" @enderror />
                    @error('formCursec') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">División</label>
                    <input type="text" wire:model.blur="formC" maxlength="1" class="input" placeholder="A, B…" />
                </div>
                <div>
                    <label class="label">Turno</label>
                    <select wire:model.blur="formTurno" class="input">
                        <option value="">Sin especificar</option>
                        <option>Mañana</option>
                        <option>Tarde</option>
                        <option>Noche</option>
                        <option>Vespertino</option>
                    </select>
                </div>
                <div class="sm:col-span-3 flex items-center justify-end gap-3 pt-2 border-t border-neutral-100">
                    <x-ui.button type="button" variant="ghost" wire:click="cancelarCurso">Cancelar</x-ui.button>
                    <x-ui.button type="submit" variant="primary" icon="check">
                        {{ $editCursoId ? 'Guardar cambios' : 'Crear curso' }}
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Lista de cursos --}}
        <div>
            <x-ui.table>
                <thead>
                    <tr>
                        <x-ui.th>Curso</x-ui.th>
                        <x-ui.th>Año</x-ui.th>
                        <x-ui.th right>Acciones</x-ui.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($this->cursos as $curso)
                        <tr wire:click="seleccionarCurso({{ $curso->Id }})"
                            class="cursor-pointer tr-hover {{ $selectedCursoId === $curso->Id ? 'bg-brand-surface/40' : '' }}">
                            <x-ui.td>
                                <p class="font-medium {{ $selectedCursoId === $curso->Id ? 'text-primary-700' : 'text-brand-jet' }}">{{ $curso->cursec }}</p>
                                <p class="text-2xs text-neutral-400">{{ $curso->nivel?->abrev }} {{ $curso->turno ? '· ' . $curso->turno : '' }}</p>
                            </x-ui.td>
                            <x-ui.td><x-ui.badge variant="neutral">{{ $curso->terlec?->ano }}</x-ui.badge></x-ui.td>
                            <x-ui.td right wire:click.stop>
                                @if($confirmDeleteCursoId == $curso->Id)
                                    <div class="flex items-center justify-end gap-2">
                                        <x-ui.button size="sm" variant="danger" wire:click="borrarCurso">Sí</x-ui.button>
                                        <x-ui.button size="sm" variant="ghost" wire:click="cancelarCurso">No</x-ui.button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="btn-icon" wire:click="editarCurso({{ $curso->Id }})" title="Editar"><x-icons.pencil class="w-4 h-4"/></button>
                                        <button class="btn-icon-danger" wire:click="confirmarBorrarCurso({{ $curso->Id }})" title="Borrar"><x-icons.trash class="w-4 h-4"/></button>
                                    </div>
                                @endif
                            </x-ui.td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-0">
                            <x-ui.empty-state title="Sin cursos" message="No hay cursos para los filtros seleccionados." icon="academic-cap" />
                        </td></tr>
                    @endforelse
                </tbody>
            </x-ui.table>
            <p class="mt-2 text-xs text-neutral-400">Clic en un curso para ver sus materias →</p>
        </div>

        {{-- Materias del curso seleccionado --}}
        <div>
            @if($selectedCursoId)
                @php $curso = $this->cursos->firstWhere('Id', $selectedCursoId) @endphp
                <x-ui.card :padding="false">
                    <x-slot:title>{{ $curso?->cursec }} ({{ $curso?->terlec?->ano }})</x-slot:title>
                    <x-slot:actions>
                        @if(!$showMateriaForm)
                            <x-ui.button size="sm" wire:click="crearMateria" icon="plus">Agregar</x-ui.button>
                        @endif
                    </x-slot:actions>

                    @if($showMateriaForm)
                        <div class="px-5 py-4 border-b border-neutral-100 bg-neutral-50">
                            <x-ui.alert variant="warning" class="mb-3 text-xs">
                                Al renombrar una materia, se actualiza también la materia modelo de origen.
                            </x-ui.alert>
                            <form wire:submit="guardarMateria" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div class="sm:col-span-3">
                                    <label class="label label-required">Nombre de la materia</label>
                                    <input type="text" wire:model.blur="formMateria" maxlength="70"
                                           class="input @error('formMateria') input-error @enderror" />
                                    @error('formMateria') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Orden</label>
                                    <input type="number" wire:model.blur="formMateriaOrd" min="1" class="input" />
                                </div>
                                <div>
                                    <label class="label">Abrev.</label>
                                    <input type="text" wire:model.blur="formMateriaAbrev" maxlength="5" class="input" />
                                </div>
                                <div class="flex items-end gap-2">
                                    <x-ui.button type="submit" variant="primary" size="sm" class="flex-1">Guardar</x-ui.button>
                                    <x-ui.button type="button" variant="ghost" size="sm" wire:click="cancelarMateria" class="flex-1">Cancelar</x-ui.button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <x-ui.th>#</x-ui.th>
                                <x-ui.th>Materia</x-ui.th>
                                <x-ui.th right>Acciones</x-ui.th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            @forelse($this->materias as $mat)
                                <tr class="tr-hover">
                                    <x-ui.td><span class="text-neutral-400">{{ $mat->ord }}</span></x-ui.td>
                                    <x-ui.td>{{ $mat->materia }}</x-ui.td>
                                    <x-ui.td right>
                                        @if($confirmDeleteMateriaId == $mat->id)
                                            <div class="flex items-center justify-end gap-2">
                                                <x-ui.button size="sm" variant="danger" wire:click="borrarMateria">Sí</x-ui.button>
                                                <x-ui.button size="sm" variant="ghost" wire:click="cancelarMateria">No</x-ui.button>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-end gap-1">
                                                <button class="btn-icon" wire:click="editarMateria({{ $mat->id }})" title="Editar"><x-icons.pencil class="w-4 h-4"/></button>
                                                <button class="btn-icon-danger" wire:click="confirmarBorrarMateria({{ $mat->id }})" title="Borrar"><x-icons.trash class="w-4 h-4"/></button>
                                            </div>
                                        @endif
                                    </x-ui.td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="p-0">
                                    <x-ui.empty-state title="Sin materias" message="Agregá materias al curso." icon="document-text" />
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-ui.card>
            @else
                <div class="flex h-full min-h-[200px] items-center justify-center rounded-xl border-2 border-dashed border-neutral-200 p-8 text-center">
                    <div>
                        <x-icons.academic-cap class="w-8 h-8 text-neutral-300 mx-auto mb-2" />
                        <p class="text-sm text-neutral-400">Seleccioná un curso para ver y editar sus materias</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
