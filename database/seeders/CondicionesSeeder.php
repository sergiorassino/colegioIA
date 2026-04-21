<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CondicionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('condiciones')->insertOrIgnore([
            ['id' => 1, 'orden' =>  1, 'condicion' => 'REGULAR',               'proteg' => 0],
            ['id' => 2, 'orden' =>  2, 'condicion' => 'PASE',                  'proteg' => 0],
            ['id' => 3, 'orden' =>  3, 'condicion' => 'LIBRE',                 'proteg' => 0],
            ['id' => 4, 'orden' =>  4, 'condicion' => 'ABANDONÓ ESTUDIOS',     'proteg' => 0],
            ['id' => 5, 'orden' => 10, 'condicion' => 'Cursado en otro Colegio','proteg' => 99],
            ['id' => 6, 'orden' =>  8, 'condicion' => 'Años vacíos en Pase s/m','proteg' => 99],
            ['id' => 7, 'orden' => 11, 'condicion' => 'No comienza el año',     'proteg' => 99],
            ['id' => 8, 'orden' =>  9, 'condicion' => 'Años vacíos en Pase c/m','proteg' => 99],
            ['id' => 9, 'orden' => 12, 'condicion' => '----------------------', 'proteg' => 99],
        ]);
    }
}
