<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Amplía pwrd para soportar bcrypt (60+ caracteres) sin romper el login híbrido.
 * MySQL/MariaDB legacy suele tener VARCHAR(10), que trunca el hash del seeder.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        // ALTER puede revalidar toda la fila; fechas legacy 0000-00-00 fallan con sql_mode estricto.
        DB::statement('SET @__colegio_prev_sql_mode := @@SESSION.sql_mode');
        DB::statement("SET SESSION sql_mode = REPLACE(REPLACE(REPLACE(@@SESSION.sql_mode, 'NO_ZERO_DATE', ''), 'NO_ZERO_IN_DATE', ''), 'STRICT_TRANS_TABLES', '')");

        try {
            if (Schema::hasTable('profesores')) {
                DB::statement("ALTER TABLE `profesores` MODIFY `pwrd` VARCHAR(255) NOT NULL DEFAULT ''");
            }

            if (Schema::hasTable('legajos')) {
                DB::statement("ALTER TABLE `legajos` MODIFY `pwrd` VARCHAR(255) NOT NULL DEFAULT ''");
            }
        } finally {
            DB::statement('SET SESSION sql_mode = @__colegio_prev_sql_mode');
        }
    }

    public function down(): void
    {
        // Revertir longitud puede truncar hashes existentes; se deja vacío a propósito.
    }
};
