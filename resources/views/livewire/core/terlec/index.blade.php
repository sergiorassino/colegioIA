<div>
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Ciclos lectivos</h1>
        @if(!$showForm)
            <button wire:click="crear" class="mt-3 sm:mt-0 inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                + Nuevo ciclo lectivo
            </button>
        @endif
    </div>

    {{-- Mensajes flash --}}
    @if(session('success'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    {{-- Formulario --}}
    @if($showForm)
        <div class="mb-6 bg-white rounded-lg shadow ring-1 ring-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                {{ $editId ? 'Editar ciclo lectivo' : 'Nuevo ciclo lectivo' }}
            </h2>
            <form wire:submit="guardar" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
                    <input type="number" wire:model="ano" min="1900" max="2100"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('ano') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Orden (1 = más reciente)</label>
                    <input type="number" wire:model="orden" min="1"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @error('orden') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 flex gap-3 justify-end">
                    <button type="button" wire:click="cancelar" class="rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500" wire:loading.attr="disabled">
                        <span wire:loading.remove>Guardar</span>
                        <span wire:loading>Guardando...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-white rounded-lg shadow ring-1 ring-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Año</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orden</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($this->terlecs as $terlec)
                    <tr>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $terlec->ano }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $terlec->orden }}</td>
                        <td class="px-6 py-4 text-right space-x-3">
                            @if($confirmDeleteId == $terlec->id)
                                <span class="text-sm text-red-600 font-medium">¿Confirmar?</span>
                                <button wire:click="borrar" class="text-sm text-red-600 hover:text-red-800 font-medium">Sí, borrar</button>
                                <button wire:click="cancelar" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</button>
                            @else
                                <button wire:click="editar({{ $terlec->id }})" class="text-sm text-indigo-600 hover:text-indigo-800">Editar</button>
                                <button wire:click="confirmarBorrar({{ $terlec->id }})" class="text-sm text-red-500 hover:text-red-700">Borrar</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-sm text-gray-400">No hay ciclos lectivos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
