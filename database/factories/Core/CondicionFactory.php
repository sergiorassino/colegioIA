<?php

namespace Database\Factories\Core;

use App\Models\Core\Condicion;
use Illuminate\Database\Eloquent\Factories\Factory;

class CondicionFactory extends Factory
{
    protected $model = Condicion::class;

    public function definition(): array
    {
        return [
            'orden'    => $this->faker->unique()->numberBetween(1, 50),
            'condicion'=> $this->faker->randomElement(['REGULAR', 'PASE', 'LIBRE']),
            'proteg'   => 0,
        ];
    }
}
