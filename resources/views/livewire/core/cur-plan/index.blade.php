<div>
    <x-ui.page-header
        title="Cursos modelo"
        subtitle="Cursos y materias modelo para generar cursos del año"
        :breadcrumbs="[['label' => 'Inicio', 'href' => route('staff.dashboard')], ['label' => 'Cursos modelo']]"
    >
        <x-slot:actions>
            @if(!$showCurPlanForm)
                <x-ui.button wire:click="crearCurPlan" icon="plus">Nuevo curso modelo</x-ui.button>
            @endif
        </x-slot:actions>
    </x-ui.page-header>

    @if(session('success')) <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert> @endif
    @if(session('error'))   <x-ui.alert variant="danger"  class="mb-4">{{ session('error') }}</x-ui.alert>   @endif

    {{-- Formulario CurPlan --}}
    @if($showCurPlanForm)
        <x-ui.card class="mb-6">
            <x-slot:title>{{ $editCurPlanId ? 'Editar curso modelo' : 'Nuevo curso modelo' }}</x-slot:title>
            <form wire:submit="guardarCurPlan" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="label label-required">Plan de estudio</label>
                    <select wire:model.blur="idPlan" class="input @error('idPlan') input-error @enderror">
                        <option value="">Seleccione un plan…</option>
                        @foreach($this->planes as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->nivel?->abrev }} — {{ $plan->plan }}</option>
                        @endforeach
                    </select>
                    @error('idPlan') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label label-required">Nombre del curso modelo</label>
                    <input type="text" wire:model.blur="curPlanCurso" maxlength="100"
                           class="input @error('curPlanCurso') input-error @enderror" placeholder="Ej: 1° A, Sala de 4"
                           @error('curPlanCurso') aria-invalid="true" @enderror />
                    @error('curPlanCurso') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 flex items-center justify-end gap-3 pt-2 border-t border-neutral-100">
                    <x-ui.button type="button" variant="ghost" wire:click="cancelarCurPlan">Cancelar</x-ui.button>
                    <x-ui.button type="submit" variant="primary" icon="check">Guardar</x-ui.button>
                </div>
            </form>
        </x-ui.card>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Lista Cursos Modelo --}}
        <div>
            <x-ui.table>
                <thead>
                    <tr>
                        <x-ui.th>Curso</x-ui.th>
                        <x-ui.th>Plan</x-ui.th>
                        <x-ui.th right>Acciones</x-ui.th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($this->curplanes as $curplan)
                        <tr wire:click="seleccionarCurPlan({{ $curplan->id }})"
                            class="cursor-pointer tr-hover {{ $selectedCurPlanId === $curplan->id ? 'bg-brand-surface/40' : '' }}">
                            <x-ui.td>
                                <span class="font-medium {{ $selectedCurPlanId === $curplan->id ? 'text-primary-700' : 'text-brand-jet' }}">
                                    {{ $curplan->curPlanCurso }}
                                </span>
                            </x-ui.td>
                            <x-ui.td><x-ui.badge variant="neutral">{{ $curplan->plan?->abrev }}</x-ui.badge></x-ui.td>
                            <x-ui.td right wire:click.stop>
                                @if($confirmDeleteCurPlanId == $curplan->id)
                                    <div class="flex items-center justify-end gap-2">
                                        <x-ui.button size="sm" variant="danger" wire:click="borrarCurPlan">Sí</x-ui.button>
                                        <x-ui.button size="sm" variant="ghost" wire:click="cancelarCurPlan">No</x-ui.button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-end gap-1">
                                        <button class="btn-icon" wire:click="editarCurPlan({{ $curplan->id }})" title="Editar"><x-icons.pencil class="w-4 h-4"/></button>
                                        <button class="btn-icon-danger" wire:click="confirmarBorrarCurPlan({{ $curplan->id }})" title="Borrar"><x-icons.trash class="w-4 h-4"/></button>
                                    </div>
                                @endif
                            </x-ui.td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="p-0">
                            <x-ui.empty-state title="Sin cursos modelo" message="No hay cursos modelo. Hacé clic en «Nuevo curso modelo»." icon="academic-cap" />
                        </td></tr>
                    @endforelse
                </tbody>
            </x-ui.table>
            <p class="mt-2 text-xs text-neutral-400">Clic en un curso para ver sus materias modelo →</p>
        </div>

        {{-- Materias modelo del curso seleccionado --}}
        <div>
            @if($selectedCurPlanId)
                @php $curplan = $this->curplanes->firstWhere('id', $selectedCurPlanId) @endphp
                <x-ui.card :padding="false">
                    <x-slot:title>Materias — {{ $curplan?->curPlanCurso }}</x-slot:title>
                    <x-slot:actions>
                        @if(!$showMatPlanForm)
                            <x-ui.button size="sm" wire:click="crearMatPlan" icon="plus">Agregar</x-ui.button>
                        @endif
                    </x-slot:actions>

                    {{-- Form MatPlan --}}
                    @if($showMatPlanForm)
                        <div class="px-5 py-4 border-b border-neutral-100 bg-neutral-50">
                            <form wire:submit="guardarMatPlan" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div class="sm:col-span-3">
                                    <label class="label label-required">Nombre de la materia</label>
                                    <input type="text" wire:model.blur="matPlanMateria" maxlength="200"
                                           class="input @error('matPlanMateria') input-error @enderror" />
                                    @error('matPlanMateria') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Orden</label>
                                    <input type="number" wire:model.blur="matPlanOrd" min="1"
                                           class="input @error('matPlanOrd') input-error @enderror" />
                                    @error('matPlanOrd') <p class="error-msg"><x-icons.exclamation-triangle class="w-3.5 h-3.5"/>{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="label">Abreviatura</label>
                                    <input type="text" wire:model.blur="matPlanAbrev" maxlength="10" class="input" />
                                </div>
                                <div class="flex items-end gap-2">
                                    <x-ui.button type="submit" variant="primary" size="sm" class="flex-1">Guardar</x-ui.button>
                                    <x-ui.button type="button" variant="ghost" size="sm" wire:click="cancelarMatPlan" class="flex-1">Cancelar</x-ui.button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <x-ui.th>#</x-ui.th>
                                <x-ui.th>Materia</x-ui.th>
                                <x-ui.th>Abrev.</x-ui.th>
                                <x-ui.th right>Acciones</x-ui.th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100">
                            @forelse($this->matplanes as $mat)
                                <tr class="tr-hover">
                                    <x-ui.td><span class="text-neutral-400">{{ $mat->ord }}</span></x-ui.td>
                                    <x-ui.td>{{ $mat->matPlanMateria }}</x-ui.td>
                                    <x-ui.td>{{ $mat->abrev }}</x-ui.td>
                                    <x-ui.td right>
                                        @if($confirmDeleteMatPlanId == $mat->id)
                                            <div class="flex items-center justify-end gap-2">
                                                <x-ui.button size="sm" variant="danger" wire:click="borrarMatPlan">Sí</x-ui.button>
                                                <x-ui.button size="sm" variant="ghost" wire:click="cancelarMatPlan">No</x-ui.button>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-end gap-1">
                                                <button class="btn-icon" wire:click="editarMatPlan({{ $mat->id }})" title="Editar"><x-icons.pencil class="w-4 h-4"/></button>
                                                <button class="btn-icon-danger" wire:click="confirmarBorrarMatPlan({{ $mat->id }})" title="Borrar"><x-icons.trash class="w-4 h-4"/></button>
                                            </div>
                                        @endif
                                    </x-ui.td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-0">
                                    <x-ui.empty-state title="Sin materias" message="Agregá la primera materia modelo." icon="document-text" />
                                </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </x-ui.card>
            @else
                <div class="flex h-full min-h-[200px] items-center justify-center rounded-xl border-2 border-dashed border-neutral-200 p-8 text-center">
                    <div>
                        <x-icons.academic-cap class="w-8 h-8 text-neutral-300 mx-auto mb-2" />
                        <p class="text-sm text-neutral-400">Seleccioná un curso modelo para ver y editar sus materias</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
