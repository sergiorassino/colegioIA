<?php

namespace App\Services;

use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MateriaIntegridadService
{
    /**
     * Renombra una materia del año.
     *
     * REGLA: Si la materia tiene idMatPlan válido (no es extraordinaria),
     * también se actualiza el nombre en la tabla matplan para mantener coherencia.
     *
     * @throws RuntimeException
     */
    public function renombrar(int $materiaId, string $nuevoNombre): Materia
    {
        return DB::transaction(function () use ($materiaId, $nuevoNombre) {
            /** @var Materia $materia */
            $materia = Materia::findOrFail($materiaId);

            $materia->update(['materia' => $nuevoNombre]);

            // Propagar nombre al matplan de origen si existe
            if ($materia->idMatPlan && $materia->idMatPlan > 0) {
                MatPlan::where('id', $materia->idMatPlan)
                    ->update(['matPlanMateria' => $nuevoNombre]);
            }

            return $materia->refresh();
        });
    }

    /**
     * Agrega una materia nueva al año y crea su matplan de origen.
     * Esta materia nueva NO pertenece a ningún curplan existente,
     * por lo que se crea un registro en matplan vinculado al idCurPlan del curso.
     */
    public function agregar(int $idCursos, int $idCurPlan, int $idNivel, int $idTerlec, string $nombre, int $ord, ?string $abrev = null): Materia
    {
        return DB::transaction(function () use ($idCursos, $idCurPlan, $idNivel, $idTerlec, $nombre, $ord, $abrev) {
            // Crear registro en matplan
            $matplan = MatPlan::create([
                'idCurPlan'      => $idCurPlan,
                'matPlanMateria' => $nombre,
                'ord'            => $ord,
                'abrev'          => $abrev,
            ]);

            // Crear materia del año vinculada al matplan nuevo
            return Materia::create([
                'idCursos'  => $idCursos,
                'idCurPlan' => $idCurPlan,
                'idMatPlan' => $matplan->id,
                'idNivel'   => $idNivel,
                'idTerlec'  => $idTerlec,
                'materia'   => $nombre,
                'ord'       => $ord,
                'abrev'     => $abrev,
            ]);
        });
    }

    /**
     * Elimina una materia del año.
     *
     * REGLA: Solo se puede eliminar si NO tiene calificaciones cargadas.
     * Si el matplan de origen no tiene otras materias referenciando a él,
     * también se elimina el matplan.
     *
     * @throws RuntimeException si tiene calificaciones asociadas.
     */
    public function eliminar(int $materiaId): void
    {
        DB::transaction(function () use ($materiaId) {
            /** @var Materia $materia */
            $materia = Materia::with('calificaciones')->findOrFail($materiaId);

            if ($materia->calificaciones()->exists()) {
                throw new RuntimeException(
                    "No se puede eliminar la materia '{$materia->materia}': tiene calificaciones registradas."
                );
            }

            $idMatPlan = $materia->idMatPlan;

            $materia->delete();

            // Si era el único vínculo al matplan, eliminar también el matplan
            if ($idMatPlan && $idMatPlan > 0) {
                $otrasReferencias = Materia::where('idMatPlan', $idMatPlan)->exists();
                if (! $otrasReferencias) {
                    MatPlan::where('id', $idMatPlan)->delete();
                }
            }
        });
    }
}
