<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesorAdminSeeder extends Seeder
{
    /**
     * Inserta o actualiza un usuario administrador de prueba.
     *
     * La contraseña va en texto plano (legacy) para que funcione aunque
     * `profesores.pwrd` sea VARCHAR(10). Tras ampliar la columna (migración
     * widen_legacy_password_columns) el primer login la rehashea a bcrypt.
     *
     * ⚠️  NO usar en producción con esta clave por defecto.
     */
    public function run(): void
    {
        $permisosCompletos = str_repeat('1', 50);

        DB::table('profesores')->updateOrInsert(
            ['id' => 9999],
            [
                'IdTipoProf'   => 2,
                'apellido'     => 'Admin',
                'nombre'       => 'Sistema',
                'dni'          => '99999999',
                'cuil'         => '',
                'sexo'         => 'M',
                'nivel'        => 3,
                'email'        => 'admin@demo.test',
                'emailInsti'   => '',
                'callenum'     => '',
                'barrio'       => '',
                'telefono'     => '',
                'nacion'       => 'Argentina',
                'estacivi'     => '',
                'legJunta'     => '',
                'legEscuela'   => '',
                'celular'      => '',
                'fechnaci'     => null,
                'titulo'       => '',
                'numreg'       => '',
                'apto'         => '',
                'incapac'      => '',
                'escalafonD'   => '',
                'escalafonE'   => '',
                'cargo'        => '',
                'obs'          => '',
                'ult_idTerlec' => null,
                'ult_idNivel'  => null,
                'pwrd'         => 'admin123',
                'permisos'     => $permisosCompletos,
            ]
        );
    }
}
