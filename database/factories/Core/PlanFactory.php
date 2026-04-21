<?php

namespace Database\Factories\Core;

use App\Models\Core\Nivel;
use App\Models\Core\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'idNivel' => Nivel::factory(),
            'plan'    => $this->faker->words(3, true),
            'abrev'   => strtoupper($this->faker->lexify('??')),
        ];
    }
}
