<x-layouts.autogestion>
    @auth('alumno')
        @php
            $alumno = auth('alumno')->user();
            $nombre = trim(($alumno->nombre ?? '') . ' ' . ($alumno->apellido ?? '')) ?: ('DNI ' . $alumno->dni);
        @endphp

        {{-- Saludo --}}
        <div class="mb-6">
            <h1 class="text-xl font-bold text-brand-jet">¡Hola, {{ $alumno->nombre ?? $alumno->dni }}!</h1>
            <p class="text-sm text-neutral-500 mt-0.5">Este es tu panel de autogestión</p>
        </div>

        {{-- Tarjetas de acceso --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('alumno.calificaciones') }}"
               wire:navigate
               class="flex flex-col items-center gap-2.5 rounded-xl bg-white border border-neutral-100 shadow-card p-5
                      hover:border-primary-200 hover:bg-brand-surface/20 active:scale-95 transition-all">
                <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                    <x-icons.document-text class="w-6 h-6 text-primary-600" />
                </div>
                <span class="text-xs font-semibold text-neutral-700">Mis notas</span>
            </a>

            <a href="{{ route('alumno.materias') }}"
               wire:navigate
               class="flex flex-col items-center gap-2.5 rounded-xl bg-white border border-neutral-100 shadow-card p-5
                      hover:border-primary-200 hover:bg-brand-surface/20 active:scale-95 transition-all">
                <div class="h-12 w-12 rounded-full bg-brand-surface flex items-center justify-center">
                    <x-icons.academic-cap class="w-6 h-6 text-primary-600" />
                </div>
                <span class="text-xs font-semibold text-neutral-700">Materias</span>
            </a>

            <a href="{{ route('alumno.perfil') }}"
               wire:navigate
               class="flex flex-col items-center gap-2.5 rounded-xl bg-white border border-neutral-100 shadow-card p-5
                      hover:border-primary-200 hover:bg-brand-surface/20 active:scale-95 transition-all col-span-2">
                <div class="h-12 w-12 rounded-full bg-neutral-100 flex items-center justify-center">
                    <x-icons.user class="w-6 h-6 text-neutral-500" />
                </div>
                <span class="text-xs font-semibold text-neutral-700">Mi perfil</span>
            </a>
        </div>

    @endauth
</x-layouts.autogestion>
