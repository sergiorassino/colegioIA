<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesorTipoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('profesortipo')->insertOrIgnore([
            ['id' => 1, 'tipo' => 'Sin Rol',         'accesoMenu' => 1],
            ['id' => 2, 'tipo' => 'Directivo',        'accesoMenu' => 2],
            ['id' => 3, 'tipo' => 'Secretario/a',     'accesoMenu' => 3],
            ['id' => 4, 'tipo' => 'Preceptor/a',      'accesoMenu' => 4],
            ['id' => 5, 'tipo' => 'Bibliotecario/a',  'accesoMenu' => 5],
            ['id' => 6, 'tipo' => 'Profesor/a',       'accesoMenu' => 6],
            ['id' => 8, 'tipo' => 'ATP / DOE',        'accesoMenu' => 7],
        ]);
    }
}
