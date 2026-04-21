<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NivelesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('niveles')->insertOrIgnore([
            ['id' => 1, 'nivel' => 'Nivel Inicial',   'abrev' => 'Inic'],
            ['id' => 2, 'nivel' => 'Nivel Primario',  'abrev' => 'Prim'],
            ['id' => 3, 'nivel' => 'Nivel Secundario','abrev' => 'Secu'],
            ['id' => 4, 'nivel' => 'Nivel Terciario', 'abrev' => 'Terc'],
            ['id' => 5, 'nivel' => 'Administración',  'abrev' => 'Admi'],
        ]);
    }
}
