<?php

namespace Database\Factories\Core;

use App\Models\Core\CurPlan;
use App\Models\Core\Curso;
use App\Models\Core\Nivel;
use App\Models\Core\Terlec;
use Illuminate\Database\Eloquent\Factories\Factory;

class CursoFactory extends Factory
{
    protected $model = Curso::class;

    public function definition(): array
    {
        return [
            'idTerlec'  => Terlec::factory(),
            'idNivel'   => Nivel::factory(),
            'idCurPlan' => CurPlan::factory(),
            'cursec'    => $this->faker->randomElement(['1er Año A', '2do Año B', '3er Año']),
            'c'         => $this->faker->randomElement(['A', 'B', 'C', null]),
            's'         => null,
            'turno'     => $this->faker->randomElement(['Mañana', 'Tarde', null]),
            'orden'     => $this->faker->numberBetween(1, 30),
        ];
    }
}
