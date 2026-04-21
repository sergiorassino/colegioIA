<?php

namespace Database\Factories\Core;

use App\Models\Core\CurPlan;
use App\Models\Core\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurPlanFactory extends Factory
{
    protected $model = CurPlan::class;

    public function definition(): array
    {
        return [
            'idPlan'       => Plan::factory(),
            'curPlanCurso' => $this->faker->randomElement(['1° A', '2° B', '3° C', 'Sala de 4', 'Sala de 5', '1er Grado', '2do Grado']),
        ];
    }
}
