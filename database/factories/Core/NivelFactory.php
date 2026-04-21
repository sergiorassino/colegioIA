<?php

namespace Database\Factories\Core;

use App\Models\Core\Nivel;
use Illuminate\Database\Eloquent\Factories\Factory;

class NivelFactory extends Factory
{
    protected $model = Nivel::class;

    public function definition(): array
    {
        return [
            'nivel' => $this->faker->unique()->randomElement([
                'Nivel Inicial', 'Nivel Primario', 'Nivel Secundario', 'Nivel Terciario', 'Administración',
            ]),
            'abrev' => $this->faker->lexify('??'),
        ];
    }
}
