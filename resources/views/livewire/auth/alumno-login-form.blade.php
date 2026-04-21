<div class="px-8 py-8">
    {{-- Encabezado --}}
    <div class="mb-7">
        <h1 class="text-lg font-bold text-brand-jet">Autogestión de alumnos</h1>
        <p class="text-xs text-neutral-500 mt-1">Ingresá con tu DNI y contraseña</p>
    </div>

    {{-- Error global --}}
    @if (session('auth_error'))
        <x-ui.alert variant="danger" class="mb-5">
            {{ session('auth_error') }}
        </x-ui.alert>
    @endif

    <form wire:submit="login" class="space-y-4" novalidate>

        {{-- DNI --}}
        <div>
            <label for="dni" class="label label-required">DNI</label>
            <div class="relative">
                <input
                    id="dni"
                    type="text"
                    wire:model.blur="dni"
                    autocomplete="username"
                    inputmode="numeric"
                    placeholder="Tu número de DNI"
                    class="input pr-10 @error('dni') input-error @enderror"
                    aria-required="true"
                    @error('dni') aria-invalid="true" aria-describedby="dni-error" @enderror
                />
                <x-icons.user class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" />
            </div>
            @error('dni')
                <p id="dni-error" class="error-msg" role="alert">
                    <x-icons.exclamation-triangle class="w-3.5 h-3.5 shrink-0" />
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Contraseña --}}
        <div>
            <label for="password" class="label label-required">Contraseña</label>
            <div class="relative">
                <input
                    id="password"
                    type="password"
                    wire:model.blur="password"
                    autocomplete="current-password"
                    placeholder="Contraseña"
                    class="input pr-10 @error('password') input-error @enderror"
                    aria-required="true"
                    @error('password') aria-invalid="true" aria-describedby="pwd-error" @enderror
                />
                <x-icons.lock-closed class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400 pointer-events-none" />
            </div>
            @error('password')
                <p id="pwd-error" class="error-msg" role="alert">
                    <x-icons.exclamation-triangle class="w-3.5 h-3.5 shrink-0" />
                    {{ $message }}
                </p>
            @enderror
        </div>

        {{-- Botón --}}
        <div class="pt-2">
            <button
                type="submit"
                class="btn-primary btn-lg w-full justify-center"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="login">
                    <x-icons.key class="w-4 h-4" />
                    Ingresar
                </span>
                <span wire:loading wire:target="login" class="inline-flex items-center gap-1.5">
                    <svg class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8h4z"/>
                    </svg>
                    Verificando…
                </span>
            </button>
        </div>
    </form>

    {{-- Link staff --}}
    <div class="mt-6 text-center border-t border-neutral-100 pt-5">
        <a href="{{ route('staff.login') }}"
           wire:navigate
           class="btn-link text-xs">
            Acceso para personal →
        </a>
    </div>
</div>
