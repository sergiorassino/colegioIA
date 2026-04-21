<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Entorno institucional</h1>
        <p class="mt-1 text-sm text-gray-500">Configure los datos institucionales y parámetros de autogestión por nivel.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Formulario de edición --}}
    @if($showForm)
        <div class="mb-6 bg-white rounded-lg shadow ring-1 ring-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Editar entorno</h2>
            <form wire:submit="guardar" class="space-y-6">

                {{-- Datos institucionales --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Datos institucionales</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nivel</label>
                            <select wire:model="idNivel" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" disabled>
                                @foreach($this->niveles as $n)
                                    <option value="{{ $n->id }}">{{ $n->nivel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CUE</label>
                            <input type="text" wire:model="cue" maxlength="20"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la institución</label>
                            <input type="text" wire:model="insti" maxlength="255"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">CUIT</label>
                            <input type="text" wire:model="cuit" maxlength="14"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="text" wire:model="telefono" maxlength="50"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                            <input type="text" wire:model="direccion" maxlength="100"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Localidad</label>
                            <input type="text" wire:model="localidad" maxlength="100"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model="mail" maxlength="100"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                {{-- Autogestión --}}
                <div class="border-t border-gray-200 pt-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Autogestión</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ciclo lectivo para la plataforma de autogestión</label>
                            <select wire:model="idTerlecVerNotas" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Sin asignar</option>
                                @foreach($this->terlecs as $terlec)
                                    <option value="{{ $terlec->id }}">{{ $terlec->ano }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Flags de disponibilidad --}}
                <div class="border-t border-gray-200 pt-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">Disponibilidad del sistema</h3>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="platOff" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Plataforma de autogestión <strong>deshabilitada</strong></span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="verNotasOff" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Visualización de notas <strong>deshabilitada</strong></span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="cargaNotasOff" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Carga de calificaciones <strong>deshabilitada</strong></span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="matriculaWebOff" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700">Matrícula web <strong>deshabilitada</strong></span>
                        </label>
                    </div>
                    <div class="mt-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mensaje cuando la plataforma está deshabilitada</label>
                        <textarea wire:model="offMensaje" rows="2" maxlength="500"
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
                    <button type="button" wire:click="cancelar" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Cancelar</button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Lista de entornos --}}
    @if(!$showForm)
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($this->entos as $ento)
                <div class="bg-white rounded-lg shadow ring-1 ring-gray-200 p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-800">
                                {{ $ento->nivel?->abrev }}
                            </span>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900">{{ $ento->nivel?->nivel }}</h3>
                        </div>
                        <button wire:click="editar({{ $ento->Id }})" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Editar
                        </button>
                    </div>
                    <dl class="space-y-1 text-xs text-gray-500">
                        <dt class="font-medium text-gray-700">{{ $ento->insti }}</dt>
                        @if($ento->terlecAutogesion)
                            <dd>Autogestión: <span class="font-medium text-gray-700">{{ $ento->terlecAutogesion->ano }}</span></dd>
                        @else
                            <dd class="text-amber-600">Sin ciclo de autogestión asignado</dd>
                        @endif
                        @if($ento->platOff)
                            <dd class="text-red-600 font-medium">⚠ Plataforma deshabilitada</dd>
                        @endif
                    </dl>
                </div>
            @endforeach
        </div>
    @endif
</div>
