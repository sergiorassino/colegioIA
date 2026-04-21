<?php

namespace Database\Factories\Core;

use App\Models\Core\CurPlan;
use App\Models\Core\Curso;
use App\Models\Core\Materia;
use App\Models\Core\MatPlan;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Illuminate\Database\Eloquent\Factories\Factory;

class MateriaFactory extends Factory
{
    protected $model = Materia::class;

    public function definition(): array
    {
        return [
            'ord'       => $this->faker->numberBetween(1, 20),
            'idCurPlan' => CurPlan::factory(),
            'idMatPlan' => MatPlan::factory(),
            'idNivel'   => Nivel::factory(),
            'idCursos'  => Curso::factory(),
            'idTerlec'  => Terlec::factory(),
            'materia'   => $this->faker->words(2, true),
            'abrev'     => strtoupper($this->faker->lexify('???')),
            'cierre1e'  => 0,
            'cierre2e'  => 0,
        ];
    }
}
