<?php

namespace Database\Factories\Core;

use App\Models\Core\CurPlan;
use App\Models\Core\MatPlan;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatPlanFactory extends Factory
{
    protected $model = MatPlan::class;

    public function definition(): array
    {
        return [
            'idCurPlan'      => CurPlan::factory(),
            'matPlanMateria' => $this->faker->words(2, true),
            'ord'            => $this->faker->numberBetween(1, 20),
            'abrev'          => strtoupper($this->faker->lexify('???')),
            'codGE'          => null,
            'codGE2'         => null,
            'codGE3'         => null,
        ];
    }
}
