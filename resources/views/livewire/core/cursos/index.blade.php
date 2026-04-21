<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Cursos del año</h1>
        @if(!$showCursoForm)
            <button wire:click="crearCurso" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                + Nuevo curso
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Filtros --}}
    <div class="mb-4 flex flex-wrap gap-3">
        <select wire:model.live="filtroTerlec" class="rounded-md border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Todos los ciclos lectivos</option>
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

    {{-- Formulario de curso --}}
    @if($showCursoForm)
        <div class="mb-6 bg-white rounded-lg shadow ring-1 ring-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                {{ $editCursoId ? 'Editar curso' : 'Nuevo curso del año' }}
            </h2>
            @if(!$editCursoId)
                <div class="mb-4 rounded-md bg-amber-50 border border-amber-200 p-3 text-xs text-amber-800">
                    <strong>Al crear un curso</strong>, se copiarán automáticamente todas las materias modelo del curso elegido para el año lectivo seleccionado.
                </div>
            @endif
            <form wire:submit="guardarCurso" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ciclo lectivo</label>
                    <select wire:model="formTerlec" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccione...</option>
                        @foreach($this->terlecs as $t)
                            <option value="{{ $t->id }}">{{ $t->ano }}</option>
                        @endforeach
                    </select>
                    @error('formTerlec') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nivel</label>
                    <select wire:model.live="formNivel" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Seleccione...</option>
                        @foreach($this->niveles as $n)
                            <option value="{{ $n->id }}">{{ $n->nivel }}</option>
                        @endforeach
                    </select>
                    @error('formNivel') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Curso modelo</label>
                    <select wire:model="formCurPlan" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" @disabled(!$formNivel)>
                        <option value="">Seleccione un curso modelo...</option>
                        @foreach($this->curplanes as $cp)
                            <option value="{{ $cp->id }}">{{ $cp->curPlanCurso }}</option>
                        @endforeach
                    </select>
                    @error('formCurPlan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del curso</label>
                    <input type="text" wire:model="formCursec" maxlength="30"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="Ej: 1er Año A">
                    @error('formCursec') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">División</label>
                    <input type="text" wire:model="formC" maxlength="1"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           placeholder="A, B...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Turno</label>
                    <select wire:model="formTurno" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">Sin especificar</option>
                        <option value="Mañana">Mañana</option>
                        <option value="Tarde">Tarde</option>
                        <option value="Noche">Noche</option>
                        <option value="Vespertino">Vespertino</option>
                    </select>
                </div>
                <div class="sm:col-span-3 flex gap-3 justify-end">
                    <button type="button" wire:click="cancelarCurso" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500" wire:loading.attr="disabled">
                        <span wire:loading.remove>{{ $editCursoId ? 'Guardar cambios' : 'Crear curso' }}</span>
                        <span wire:loading>Procesando...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Lista de cursos --}}
        <div>
            <div class="bg-white rounded-lg shadow ring-1 ring-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Curso</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Año</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($this->cursos as $curso)
                            <tr wire:click="seleccionarCurso({{ $curso->Id }})"
                                @class(['cursor-pointer hover:bg-gray-50', 'bg-indigo-50' => $selectedCursoId === $curso->Id])>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $curso->cursec }}</div>
                                    <div class="text-xs text-gray-400">{{ $curso->nivel?->abrev }} · {{ $curso->turno }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500">{{ $curso->terlec?->ano }}</td>
                                <td class="px-4 py-3 text-right space-x-2" wire:click.stop>
                                    @if($confirmDeleteCursoId == $curso->Id)
                                        <button wire:click="borrarCurso" class="text-xs text-red-600 font-medium">Sí, borrar</button>
                                        <button wire:click="cancelarCurso" class="text-xs text-gray-500">Cancelar</button>
                                    @else
                                        <button wire:click="editarCurso({{ $curso->Id }})" class="text-xs text-indigo-600 hover:text-indigo-800">Editar</button>
                                        <button wire:click="confirmarBorrarCurso({{ $curso->Id }})" class="text-xs text-red-500 hover:text-red-700">Borrar</button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-400">No hay cursos para los filtros seleccionados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Materias del curso seleccionado --}}
        <div>
            @if($selectedCursoId)
                @php $curso = $this->cursos->firstWhere('Id', $selectedCursoId) @endphp
                <div class="bg-white rounded-lg shadow ring-1 ring-gray-200 overflow-hidden">
                    <div class="px-4 py-3 border-b border-gray-200 bg-indigo-50 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-indigo-900">
                            Materias — {{ $curso?->cursec }} ({{ $curso?->terlec?->ano }})
                        </h3>
                        @if(!$showMateriaForm)
                            <button wire:click="crearMateria" class="text-xs rounded-md bg-indigo-600 text-white px-3 py-1.5 font-semibold hover:bg-indigo-500">
                                + Agregar
                            </button>
                        @endif
                    </div>

                    @if($showMateriaForm)
                        <div class="p-4 border-b border-gray-200 bg-gray-50">
                            <div class="mb-2 text-xs text-amber-700 font-medium">
                                ⚠ Al renombrar se actualiza también la materia modelo de origen.
                            </div>
                            <form wire:submit="guardarMateria" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div class="sm:col-span-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Nombre de la materia</label>
                                    <input type="text" wire:model="formMateria" maxlength="70"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    @error('formMateria') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Orden</label>
                                    <input type="number" wire:model="formMateriaOrd" min="1"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Abrev.</label>
                                    <input type="text" wire:model="formMateriaAbrev" maxlength="5"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                                <div class="flex items-end gap-2">
                                    <button type="submit" class="flex-1 rounded-md bg-indigo-600 px-3 py-2 text-xs font-semibold text-white hover:bg-indigo-500" wire:loading.attr="disabled">
                                        Guardar
                                    </button>
                                    <button type="button" wire:click="cancelarMateria" class="flex-1 rounded-md bg-white px-3 py-2 text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
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
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($this->materias as $mat)
                                <tr>
                                    <td class="px-4 py-3 text-xs text-gray-400">{{ $mat->ord }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $mat->materia }}</td>
                                    <td class="px-4 py-3 text-right space-x-2">
                                        @if($confirmDeleteMateriaId == $mat->id)
                                            <button wire:click="borrarMateria" class="text-xs text-red-600 font-medium">Sí, borrar</button>
                                            <button wire:click="cancelarMateria" class="text-xs text-gray-500">Cancelar</button>
                                        @else
                                            <button wire:click="editarMateria({{ $mat->id }})" class="text-xs text-indigo-600 hover:text-indigo-800">Editar</button>
                                            <button wire:click="confirmarBorrarMateria({{ $mat->id }})" class="text-xs text-red-500 hover:text-red-700">Borrar</button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-xs text-gray-400">Sin materias del año.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex h-full min-h-[200px] items-center justify-center rounded-lg border-2 border-dashed border-gray-200 p-8 text-center">
                    <p class="text-sm text-gray-400">Seleccione un curso para ver y editar sus materias del año.</p>
                </div>
            @endif
        </div>
    </div>
</div>
