<x-layouts.autogestion>
    <x-slot:title>Mis materias</x-slot:title>

    @auth('alumno')
        <div class="mb-5">
            <h1 class="text-lg font-bold text-brand-jet">Mis materias</h1>
            <p class="text-xs text-neutral-500 mt-0.5">Materias del año en curso</p>
        </div>

        <div class="card">
            <div class="card-body">
                <x-ui.empty-state
                    title="Próximamente"
                    message="La lista de materias estará disponible cuando el sistema esté configurado."
                    icon="academic-cap"
                />
            </div>
        </div>
    @endauth
</x-layouts.autogestion>
