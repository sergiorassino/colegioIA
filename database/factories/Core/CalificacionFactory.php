<?php

namespace Database\Factories\Core;

use App\Models\Core\Calificacion;
use App\Models\Core\Curso;
use App\Models\Core\Legajo;
use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use App\Models\Core\Matricula;
use App\Models\Core\Terlec;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalificacionFactory extends Factory
{
    protected $model = Calificacion::class;

    public function definition(): array
    {
        return [
            'idLegajos'   => Legajo::factory(),
            'idMatricula' => null,
            'ord'         => $this->faker->numberBetween(1, 20),
            'idTerlec'    => Terlec::factory(),
            'idCursos'    => Curso::factory(),
            'idMaterias'  => Materia::factory(),
            'idMatPlan'   => MatPlan::factory(),
            'ic01' => '', 'ic02' => '', 'ic03' => '', 'ic04' => '',
            'ic05' => '', 'ic06' => '', 'ic07' => '', 'ic08' => '',
            'ic09' => '', 'ic10' => '', 'ic11' => '', 'ic12' => '',
            'tm1'  => '', 'tm2'  => '', 'tm3'  => '', 'tm4'  => '',
            'tm5'  => '', 'tm6'  => '',
            'dic'  => '',
            'feb'  => '',
            'apro' => null,
        ];
    }
}
