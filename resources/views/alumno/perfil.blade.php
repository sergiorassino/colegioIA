<x-layouts.autogestion>
    <x-slot:title>Mi perfil</x-slot:title>

    @auth('alumno')
        @php $alumno = auth('alumno')->user(); @endphp

        <div class="mb-5">
            <h1 class="text-lg font-bold text-brand-jet">Mi perfil</h1>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="flex items-center gap-4 mb-5">
                    <div class="h-14 w-14 rounded-full bg-primary-500 flex items-center justify-center shrink-0">
                        <span class="text-lg font-bold text-white">
                            {{ strtoupper(substr($alumno->nombre ?? $alumno->dni, 0, 2)) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-bold text-brand-jet">{{ $alumno->apellido }}, {{ $alumno->nombre }}</p>
                        <p class="text-xs text-neutral-500">DNI {{ $alumno->dni }}</p>
                        @if($alumno->nivel)
                            <x-ui.badge variant="primary" class="mt-1">{{ $alumno->nivel->nivel }}</x-ui.badge>
                        @endif
                    </div>
                </div>

                <dl class="space-y-3 text-sm">
                    @if($alumno->email)
                        <div class="flex items-center gap-2">
                            <x-icons.information-circle class="w-4 h-4 text-neutral-400 shrink-0" />
                            <div>
                                <dt class="text-2xs text-neutral-400 uppercase font-semibold tracking-wide">Email</dt>
                                <dd class="text-neutral-700">{{ $alumno->email }}</dd>
                            </div>
                        </div>
                    @endif
                    @if($alumno->telefono)
                        <div class="flex items-center gap-2">
                            <x-icons.information-circle class="w-4 h-4 text-neutral-400 shrink-0" />
                            <div>
                                <dt class="text-2xs text-neutral-400 uppercase font-semibold tracking-wide">Teléfono</dt>
                                <dd class="text-neutral-700">{{ $alumno->telefono }}</dd>
                            </div>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('alumno.logout') }}">
            @csrf
            <button type="submit" class="btn-danger w-full justify-center">
                <x-icons.arrow-right-on-rectangle class="w-4 h-4" />
                Cerrar sesión
            </button>
        </form>
    @endauth
</x-layouts.autogestion>
