<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Cursos modelo</h1>
        @if(!$showCurPlanForm)
            <button wire:click="crearCurPlan" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                + Nuevo curso modelo
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Formulario Curso Modelo --}}
    @if($showCurPlanForm)
        <div class="mb-6 bg-white rounded-lg shadow ring-1 ring-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ $editCurPlanId ? 'Editar curso modelo' : 'Nuevo curso modelo' }}</h2>
            <form wire:submit="guardarCurPlan" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plan de estudio</label>
                    <select wire:model="idPlan" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccione un plan...</option>
                        @foreach($this->planes as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->nivel?->abrev }} — {{ $plan->plan }}</option>
                        @endforeach
                    </select>
                    @error('idPlan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del curso modelo</label>
                    <input type="text" wire:model="curPlanCurso" maxlength="100"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Ej: 1° A, Sala de 4, etc.">
                    @error('curPlanCurso') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 flex gap-3 justify-end">
                    <button type="button" wire:click="cancelarCurPlan" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Lista de Cursos Modelo --}}
        <div>
            <div class="bg-white rounded-lg shadow ring-1 ring-gray-200 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                    <p class="text-sm font-medium text-gray-700">Haga clic en un curso para ver sus materias modelo</p>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($this->curplanes as $curplan)
                            <tr wire:click="seleccionarCurPlan({{ $curplan->id }})"
                                @class(['cursor-pointer hover:bg-gray-50', 'bg-indigo-50' => $selectedCurPlanId === $curplan->id])>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $curplan->curPlanCurso }}</td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $curplan->plan?->abrev }}</td>
                                <td class="px-4 py-3 text-right space-x-2" wire:click.stop>
                                    @if($confirmDeleteCurPlanId == $curplan->id)
                                        <button wire:click="borrarCurPlan" class="text-xs text-red-600 hover:text-red-800 font-medium">Sí, borrar</button>
                                        <button wire:click="cancelarCurPlan" class="text-xs text-gray-500">Cancelar</button>
                                    @else
                                        <button wire:click="editarCurPlan({{ $curplan->id }})" class="text-xs text-indigo-600 hover:text-indigo-800">Editar</button>
                                        <button wire:click="confirmarBorrarCurPlan({{ $curplan->id }})" class="text-xs text-red-500 hover:text-red-700">Borrar</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400">No hay cursos modelo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Materias Modelo del curso seleccionado --}}
        <div>
            @if($selectedCurPlanId)
                @php $curplan = $this->curplanes->firstWhere('id', $selectedCurPlanId) @endphp
                <div class="bg-white rounded-lg shadow ring-1 ring-gray-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-indigo-50 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-indigo-900">
                            Materias modelo — {{ $curplan?->curPlanCurso }}
                        </h3>
                        @if(!$showMatPlanForm)
                            <button wire:click="crearMatPlan" class="text-xs rounded-md bg-indigo-600 text-white px-3 py-1.5 font-semibold hover:bg-indigo-500">
                                + Agregar
                            </button>
                        @endif
                    </div>

                    {{-- Formulario MatPlan --}}
                    @if($showMatPlanForm)
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <form wire:submit="guardarMatPlan" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div class="sm:col-span-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nombre de la materia</label>
                                    <input type="text" wire:model="matPlanMateria" maxlength="200"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @error('matPlanMateria') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Orden</label>
                                    <input type="number" wire:model="matPlanOrd" min="1"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @error('matPlanOrd') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Abreviatura</label>
                                    <input type="text" wire:model="matPlanAbrev" maxlength="10"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div class="flex items-end gap-2">
                                    <button type="submit" class="flex-1 rounded-md bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-500" wire:loading.attr="disabled">
                                        Guardar
                                    </button>
                                    <button type="button" wire:click="cancelarMatPlan" class="flex-1 rounded-md bg-white px-3 py-2 text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Materia</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Abrev.</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($this->matplanes as $mat)
                                <tr>
                                    <td class="px-4 py-3 text-xs text-gray-400">{{ $mat->ord }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $mat->matPlanMateria }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-500">{{ $mat->abrev }}</td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        @if($confirmDeleteMatPlanId == $mat->id)
                                            <button wire:click="borrarMatPlan" class="text-xs text-red-600 font-medium">Sí, borrar</button>
                                            <button wire:click="cancelarMatPlan" class="text-xs text-gray-500">Cancelar</button>
                                        @else
                                            <button wire:click="editarMatPlan({{ $mat->id }})" class="text-xs text-indigo-600 hover:text-indigo-800">Editar</button>
                                            <button wire:click="confirmarBorrarMatPlan({{ $mat->id }})" class="text-xs text-red-500 hover:text-red-700">Borrar</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-xs text-gray-400">Sin materias modelo. Haga clic en "+ Agregar".</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex h-full items-center justify-center rounded-lg border-2 border-dashed border-gray-200 p-8 text-center">
                    <p class="text-sm text-gray-400">Seleccione un curso modelo para ver y editar sus materias.</p>
                </div>
            @endif
        </div>
    </div>
</div>
