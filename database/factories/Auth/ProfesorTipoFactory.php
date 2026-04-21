<?php

namespace Database\Factories\Auth;

use App\Models\Auth\ProfesorTipo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfesorTipoFactory extends Factory
{
    protected $model = ProfesorTipo::class;

    public function definition(): array
    {
        return [
            'tipo'       => $this->faker->randomElement(['Directivo', 'Secretario/a', 'Preceptor/a', 'Profesor/a']),
            'accesoMenu' => $this->faker->numberBetween(1, 7),
        ];
    }
}
