<?php

namespace Database\Factories\Core;

use App\Models\Core\Terlec;
use Illuminate\Database\Eloquent\Factories\Factory;

class TerlecFactory extends Factory
{
    protected $model = Terlec::class;

    private static int $ordenCounter = 1;

    public function definition(): array
    {
        return [
            'ano'   => $this->faker->unique()->numberBetween(2010, 2099),
            'orden' => static::$ordenCounter++,
        ];
    }
}
