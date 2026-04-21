<?php

namespace Database\Factories\Auth;

use App\Models\Auth\Profesor;
use App\Models\Auth\ProfesorTipo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProfesorFactory extends Factory
{
    protected $model = Profesor::class;

    public function definition(): array
    {
        return [
            'IdTipoProf'  => ProfesorTipo::factory(),
            'apellido'    => $this->faker->lastName,
            'nombre'      => $this->faker->firstName,
            'dni'         => (string) $this->faker->unique()->numberBetween(1000000, 99999999),
            'cuil'        => '',
            'sexo'        => $this->faker->randomElement(['M', 'F']),
            'nivel'       => 3,
            'email'       => $this->faker->safeEmail,
            'emailInsti'  => '',
            'callenum'    => '',
            'barrio'      => '',
            'telefono'    => '',
            'nacion'      => 'Argentina',
            'estacivi'    => '',
            'legJunta'    => '',
            'legEscuela'  => '',
            'celular'     => '',
            'titulo'      => '',
            'numreg'      => '',
            'apto'        => '',
            'incapac'     => '',
            'escalafonD'  => '',
            'escalafonE'  => '',
            'cargo'       => '',
            'obs'         => '',
            'ult_idTerlec'=> null,
            'ult_idNivel' => null,
            'pwrd'        => '123456',
            'permisos'    => str_repeat('0', 50),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $a) => [
            'permisos' => str_repeat('1', 50),
        ]);
    }

    public function director(): static
    {
        return $this->state(fn (array $a) => [
            'IdTipoProf' => 2, // Directivo
            'permisos'   => str_repeat('1', 50),
        ]);
    }

    public function profesor(): static
    {
        return $this->state(fn (array $a) => [
            'IdTipoProf' => 6, // Profesor/a
            'permisos'   => str_repeat('0', 50),
        ]);
    }
}
