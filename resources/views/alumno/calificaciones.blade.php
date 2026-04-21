<x-layouts.autogestion>
    <x-slot:title>Mis calificaciones</x-slot:title>

    @auth('alumno')
        @php $alumno = auth('alumno')->user(); @endphp

        <div class="mb-5">
            <h1 class="text-lg font-bold text-brand-jet">Mis calificaciones</h1>
            <p class="text-xs text-neutral-500 mt-0.5">Ciclo lectivo activo</p>
        </div>

        <div class="card">
            <div class="card-body">
                <x-ui.empty-state
                    title="Próximamente"
                    message="Las calificaciones estarán disponibles cuando el sistema esté configurado."
                    icon="document-text"
                />
            </div>
        </div>
    @endauth
</x-layouts.autogestion>
