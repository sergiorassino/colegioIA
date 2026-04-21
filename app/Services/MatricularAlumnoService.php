<?php

namespace App\Services;

use App\Models\Core\Calificacion;
use App\Models\Core\Curso;
use App\Models\Core\Legajo;
use App\Models\Core\Materia;
use App\Models\Core\Matricula;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MatricularAlumnoService
{
    /**
     * Matricula un alumno en un curso del año y crea los registros de calificaciones.
     *
     * @param  array{
     *   idLegajos: int,
     *   idTerlec: int,
     *   idNivel: int,
     *   idCursos: int,
     *   idCondiciones: int,
     *   fechaMatricula: string|null,
     * } $datos
     * @return Matricula
     * @throws RuntimeException si el alumno ya está matriculado en el ciclo
     */
    public function matricular(array $datos): Matricula
    {
        return DB::transaction(function () use ($datos) {
            // Verificar que no exista matrícula para el mismo terlec
            $yaMatriculado = Matricula::where('idLegajos', $datos['idLegajos'])
                ->where('idTerlec', $datos['idTerlec'])
                ->exists();

            if ($yaMatriculado) {
                throw new RuntimeException('El alumno ya tiene una matrícula para el ciclo lectivo seleccionado.');
            }

            /** @var Legajo $legajo */
            $legajo = Legajo::findOrFail($datos['idLegajos']);

            /** @var Curso $curso */
            $curso = Curso::findOrFail($datos['idCursos']);

            // Crear registro de matrícula
            $matricula = Matricula::create([
                'idTerlec'      => $datos['idTerlec'],
                'idNivel'       => $datos['idNivel'],
                'idCursos'      => $datos['idCursos'],
                'idLegajos'     => $datos['idLegajos'],
                'idCondiciones' => $datos['idCondiciones'],
                'fechaMatricula'=> $datos['fechaMatricula'] ?? now()->toDateString(),
                'inscripto'     => 1,
            ]);

            // Crear registros de calificaciones por cada materia del curso
            Materia::where('idCursos', $curso->Id)
                ->where('idTerlec', $datos['idTerlec'])
                ->orderBy('ord')
                ->each(function (Materia $materia) use ($matricula, $legajo, $datos) {
                    Calificacion::create([
                        'idLegajos'   => $legajo->id,
                        'idMatricula' => $matricula->id,
                        'ord'         => $materia->ord,
                        'idTerlec'    => $datos['idTerlec'],
                        'idCursos'    => $datos['idCursos'],
                        'idMaterias'  => $materia->id,
                        'idMatPlan'   => $materia->idMatPlan,
                    ]);
                });

            return $matricula;
        });
    }

    /**
     * Cancela una matrícula y elimina sus calificaciones (solo si no tienen notas cargadas).
     *
     * @throws RuntimeException si tiene calificaciones con notas.
     */
    public function cancelar(int $matriculaId): void
    {
        DB::transaction(function () use ($matriculaId) {
            $matricula = Matricula::with('calificaciones')->findOrFail($matriculaId);

            // Verificar que no haya notas en ninguna calificación
            $tieneNotas = $matricula->calificaciones->filter(function (Calificacion $c) {
                return filled($c->ic01)
                    || filled($c->tm1)
                    || filled($c->dic)
                    || filled($c->feb);
            })->isNotEmpty();

            if ($tieneNotas) {
                throw new RuntimeException('No se puede cancelar la matrícula: el alumno tiene calificaciones cargadas.');
            }

            $matricula->calificaciones()->delete();
            $matricula->delete();
        });
    }
}
