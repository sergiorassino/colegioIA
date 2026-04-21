<?php

namespace Database\Factories\Core;

use App\Models\Core\Legajo;
use App\Models\Core\Nivel;
use Illuminate\Database\Eloquent\Factories\Factory;

class LegajoFactory extends Factory
{
    protected $model = Legajo::class;

    public function definition(): array
    {
        return [
            'idFamilias' => 1,
            'apellido'   => $this->faker->lastName,
            'nombre'     => $this->faker->firstName,
            'dni'        => $this->faker->unique()->numberBetween(1000000, 99999999),
            'cuil'       => '',
            'tipoalumno' => 0,
            'fechnaci'   => $this->faker->dateTimeBetween('-20 years', '-5 years')->format('Y-m-d'),
            'sexo'       => $this->faker->randomElement(['M', 'F']),
            'nacion'     => 'Argentina',
            'callenum'   => $this->faker->streetAddress,
            'barrio'     => '',
            'localidad'  => 'Córdoba',
            'codpos'     => '5000',
            'telefono'   => $this->faker->phoneNumber,
            'email'      => $this->faker->safeEmail,
            'idnivel'    => Nivel::factory(),
            'nombremad'  => '',
            'dnimad'     => '',
            'vivemad'    => '',
            'nacionmad'  => '',
            'estacivimad'=> '',
            'domimad'    => '',
            'cpmad'      => '',
            'ocupacmad'  => '',
            'sitlabmad'  => '',
            'lugtramad'  => '',
            'telemad'    => '',
            'telecelmad' => '',
            'telltm'     => '',
            'emailmad'   => '',
            'nombrepad'  => '',
            'dnipad'     => '',
            'vivepad'    => '',
            'nacionpad'  => '',
            'estacivipad'=> '',
            'domipad'    => '',
            'cppad'      => '',
            'ocupacpad'  => '',
            'sitlabpad'  => '',
            'lugtrapad'  => '',
            'telepad'    => '',
            'telecelpad' => '',
            'telltp'     => '',
            'emailpad'   => '',
            'nombretut'  => '',
            'teletut'    => '',
            'emailtut'   => '',
            'ocupactut'  => '',
            'lugtratut'  => '',
            'telltt'     => '',
            'respAdmiNom'=> '',
            'respAdmiDni'=> 0,
            'escori'     => '',
            'destino'    => '',
            'emeravis'   => '',
            'retira'     => '',
            'retira1'    => '',
            'retira2'    => '',
            'identif'    => '',
            'needes'     => '',
            'vivecon'    => '',
            'acopro'     => '',
            'bloqmatr'   => 0,
            'bloqadmi'   => 0,
            'pwrd'       => '123456',
        ];
    }
}
