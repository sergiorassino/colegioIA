<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Para entornos de desarrollo/testing (no producción):
     *   php artisan db:seed
     *
     * En producción el schema ya existe; solo se ejecutan seeders
     * que usen insertOrIgnore para datos de parámetros fijos.
     */
    public function run(): void
    {
        // Datos de parametrización base (idempotentes — usan insertOrIgnore)
        $this->call([
            NivelesSeeder::class,
            TerlecSeeder::class,
            PlanesSeeder::class,
            CondicionesSeeder::class,
            ProfesorTipoSeeder::class,
            PermisosUsuariosSeeder::class,
        ]);

        // Solo para desarrollo — NO en producción
        if (app()->environment(['local', 'testing'])) {
            $this->call([
                ProfesorAdminSeeder::class,
            ]);
        }
    }
}
