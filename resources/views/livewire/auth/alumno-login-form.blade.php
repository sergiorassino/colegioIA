<div>
    <h3 class="text-lg font-semibold leading-6 text-gray-900 mb-6">
        Autogestión de alumnos
    </h3>

    <form wire:submit="login" class="space-y-5">
        {{-- DNI --}}
        <div>
            <label for="dni" class="block text-sm font-medium leading-6 text-gray-900">
                DNI
            </label>
            <div class="mt-2">
                <input
                    id="dni"
                    type="text"
                    wire:model="dni"
                    autocomplete="username"
                    inputmode="numeric"
                    class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    placeholder="Ingrese su DNI"
                >
            </div>
            @error('dni')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">
                Contraseña
            </label>
            <div class="mt-2">
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    autocomplete="current-password"
                    class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    placeholder="Contraseña"
                >
            </div>
            @error('password')
                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botón --}}
        <div>
            <button
                type="submit"
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-75"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Ingresar</span>
                <span wire:loading>Verificando...</span>
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('staff.login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
            Acceso para personal →
        </a>
    </div>
</div>
