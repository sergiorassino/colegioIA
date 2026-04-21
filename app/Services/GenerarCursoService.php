<?php

namespace App\Services;

use App\Models\Core\CurPlan;
use App\Models\Core\Curso;
use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use Illuminate\Support\Facades\DB;

class GenerarCursoService
{
    /**
     * Crea un nuevo curso para el año lectivo dado, replicando las materias
     * del CurPlan elegido hacia la tabla `materias`, preservando idMatPlan.
     *
     * @param  array{
     *   idTerlec: int,
     *   idNivel: int,
     *   idCurPlan: int,
     *   curso: string,
     *   division: string|null,
     *   turno: string|null,
     * } $datos
     * @return Curso
     */
    public function generar(array $datos): Curso
    {
        return DB::transaction(function () use ($datos) {
            /** @var CurPlan $curplan */
            $curplan = CurPlan::with('matplanes')->findOrFail($datos['idCurPlan']);

            $curso = Curso::create([
                'idTerlec'  => $datos['idTerlec'],
                'idNivel'   => $datos['idNivel'],
                'idCurPlan' => $datos['idCurPlan'],
                'cursec'    => $datos['cursec'],   // nombre del curso (ej: "1er Año A")
                'c'         => $datos['c'] ?? null, // división
                's'         => $datos['s'] ?? null, // sección
                'turno'     => $datos['turno'] ?? null,
                'orden'     => $datos['orden'] ?? null,
            ]);

            // Replicar cada materia modelo en la tabla materias
            $curplan->matplanes->each(function (MatPlan $mp) use ($curso) {
                Materia::create([
                    'idCursos'  => $curso->Id,
                    'idCurPlan' => $curso->idCurPlan,
                    'idMatPlan' => $mp->id,
                    'idTerlec'  => $curso->idTerlec,
                    'idNivel'   => $curso->idNivel,
                    'materia'   => $mp->matPlanMateria,
                    'ord'       => $mp->ord,
                    'abrev'     => $mp->abrev,
                ]);
            });

            return $curso;
        });
    }

    /**
     * Verifica que el CurPlan tenga al menos una materia modelo antes de generar.
     */
    public function validarCurPlan(int $idCurPlan): bool
    {
        return MatPlan::where('idCurPlan', $idCurPlan)->exists();
    }
}
