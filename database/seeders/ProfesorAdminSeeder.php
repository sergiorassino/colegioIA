<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfesorAdminSeeder extends Seeder
{
    /**
     * Inserta un usuario administrador de prueba.
     * ⚠️  NO ejecutar en producción — solo para desarrollo/testing.
     */
    public function run(): void
    {
        // Permiso completo: 50 dígitos en 1
        $permisosCompletos = str_repeat('1', 50);

        DB::table('profesores')->insertOrIgnore([
            'id'          => 9999,
            'IdTipoProf'  => 2,       // Directivo
            'apellido'    => 'Admin',
            'nombre'      => 'Sistema',
            'dni'         => '99999999',
            'cuil'        => '',
            'sexo'        => 'M',
            'nivel'       => 3,
            'email'       => 'admin@demo.test',
            'emailInsti'  => '',
            'callenum'    => '',
            'barrio'      => '',
            'telefono'    => '',
            'nacion'      => 'Argentina',
            'estacivi'    => '',
            'legJunta'    => '',
            'legEscuela'  => '',
            'celular'     => '',
            'fechnaci'    => null,
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
            'pwrd'        => Hash::make('admin123'),
            'permisos'    => $permisosCompletos,
        ]);
    }
}
