<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('planes')->insertOrIgnore([
            ['id' => 1, 'idNivel' => 1, 'plan' => 'Nivel Inicial',  'abrev' => 'NI'],
            ['id' => 2, 'idNivel' => 2, 'plan' => 'Nivel Primario', 'abrev' => 'NP'],
            ['id' => 3, 'idNivel' => 3, 'plan' => 'CICLO BÁSICO',   'abrev' => 'CB'],
            ['id' => 4, 'idNivel' => 3, 'plan' => 'BACHILLER EN CIENCIAS SOCIALES Y HUMANIDADES', 'abrev' => 'CS'],
            ['id' => 5, 'idNivel' => 3, 'plan' => 'BACHILLER EN CIENCIAS NATURALES',              'abrev' => 'CN'],
            ['id' => 6, 'idNivel' => 3, 'plan' => 'BACHILLER EN ECONOMÍA Y ADMINISTRACIÓN',       'abrev' => 'EA'],
        ]);
    }
}
