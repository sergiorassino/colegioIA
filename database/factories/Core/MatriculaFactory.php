<?php

namespace Database\Factories\Core;

use App\Models\Core\Condicion;
use App\Models\Core\Curso;
use App\Models\Core\Legajo;
use App\Models\Core\Matricula;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatriculaFactory extends Factory
{
    protected $model = Matricula::class;

    public function definition(): array
    {
        return [
            'idTerlec'      => Terlec::factory(),
            'idNivel'       => Nivel::factory(),
            'idCursos'      => Curso::factory(),
            'idLegajos'     => Legajo::factory(),
            'idCondiciones' => Condicion::factory(),
            'fechaMatricula'=> now()->toDateString(),
            'inscripto'     => 1,
        ];
    }
}
