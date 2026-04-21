<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para los entornos de DESARROLLO y TESTING.
 * Crea las tablas del núcleo del sistema que ya existen en producción.
 *
 * IMPORTANTE: Esta migración NO se ejecuta en producción porque:
 * - Las tablas ya existen en producción.
 * - El primer deploy usa 'php artisan migrate:install' y marca la migración como ejecutada
 *   sin ejecutarla realmente (ver docs/deploy-primer-despliegue-produccion.md).
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── niveles ──────────────────────────────────────────────
        if (! Schema::hasTable('niveles')) {
            Schema::create('niveles', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('nivel', 60)->default('');
                $table->string('abrev', 5)->nullable();
            });
        }

        // ── terlec ───────────────────────────────────────────────
        if (! Schema::hasTable('terlec')) {
            Schema::create('terlec', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('ano')->default(0);
                $table->integer('orden')->default(0);
            });
        }

        // ── planes ───────────────────────────────────────────────
        if (! Schema::hasTable('planes')) {
            Schema::create('planes', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('idNivel')->default(0);
                $table->string('plan', 70)->default('');
                $table->string('abrev', 5)->nullable();
            });
        }

        // ── curplan ──────────────────────────────────────────────
        if (! Schema::hasTable('curplan')) {
            Schema::create('curplan', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('idPlan')->default(0);
                $table->string('curPlanCurso', 100)->default('');
            });
        }

        // ── matplan ──────────────────────────────────────────────
        if (! Schema::hasTable('matplan')) {
            Schema::create('matplan', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('idCurPlan')->default(0);
                $table->string('matPlanMateria', 200)->nullable();
                $table->integer('ord')->default(0);
                $table->string('abrev', 10)->nullable();
                $table->string('codGE', 20)->nullable();
                $table->string('codGE2', 20)->nullable();
                $table->string('codGE3', 20)->nullable();
            });
        }

        // ── cursos ───────────────────────────────────────────────
        if (! Schema::hasTable('cursos')) {
            Schema::create('cursos', function (Blueprint $table) {
                $table->integer('Id')->autoIncrement();
                $table->integer('orden')->nullable();
                $table->integer('idCurPlan')->nullable();
                $table->integer('idTerlec')->nullable();
                $table->integer('idNivel')->nullable();
                $table->string('cursec', 30)->nullable();
                $table->string('c', 1)->nullable();
                $table->string('s', 1)->nullable();
                $table->string('turno', 20)->nullable();
            });
        }

        // ── materias ─────────────────────────────────────────────
        if (! Schema::hasTable('materias')) {
            Schema::create('materias', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('ord')->default(0);
                $table->integer('idCurPlan')->default(0);
                $table->integer('idMatPlan')->default(0);
                $table->integer('idNivel')->default(0);
                $table->integer('idCursos')->default(0);
                $table->integer('idTerlec')->default(0);
                $table->string('materia', 70)->nullable();
                $table->string('abrev', 5)->nullable();
                $table->integer('cierre1e')->default(0);
                $table->integer('cierre2e')->default(0);
            });
        }

        // ── condiciones ──────────────────────────────────────────
        if (! Schema::hasTable('condiciones')) {
            Schema::create('condiciones', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('orden')->default(0);
                $table->string('condicion', 60)->default('');
                $table->integer('proteg')->default(0);
            });
        }

        // ── legajos ──────────────────────────────────────────────
        if (! Schema::hasTable('legajos')) {
            Schema::create('legajos', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('idFamilias')->default(1);
                $table->integer('codigo')->nullable();
                $table->string('apellido', 50)->nullable()->default('');
                $table->string('nombre', 50)->nullable()->default('');
                $table->integer('dni')->unique()->nullable();
                $table->string('cuil', 13)->default('');
                $table->integer('tipoalumno')->default(0);
                $table->date('fechnaci')->nullable();
                $table->string('ln_ciudad', 50)->default('');
                $table->string('ln_depto', 50)->default('');
                $table->string('ln_provincia', 50)->default('');
                $table->string('ln_pais', 50)->default('');
                $table->string('sexo', 1)->nullable()->default('0');
                $table->string('nacion', 20)->default('');
                $table->string('callenum', 50)->default('');
                $table->string('barrio', 50)->default('');
                $table->string('localidad', 50)->default('');
                $table->string('codpos', 10)->default('');
                $table->string('telefono', 60)->default('');
                $table->string('email', 100)->default('');
                $table->string('nombremad', 50)->default('');
                $table->string('dnimad', 10)->default('');
                $table->string('vivemad', 2)->default('');
                $table->date('fechnacmad')->nullable();
                $table->string('nacionmad', 20)->default('');
                $table->string('estacivimad', 20)->default('');
                $table->string('domimad', 100)->default('');
                $table->string('cpmad', 4)->default('');
                $table->string('ocupacmad', 30)->default('');
                $table->string('sitlabmad', 30)->default('');
                $table->string('lugtramad', 30)->default('');
                $table->string('telemad', 50)->default('');
                $table->string('telecelmad', 50)->default('');
                $table->string('telltm', 50)->default('');
                $table->string('emailmad', 50)->default('');
                $table->string('nombrepad', 50)->default('');
                $table->string('dnipad', 10)->default('');
                $table->string('vivepad', 2)->default('');
                $table->date('fechnacpad')->nullable();
                $table->string('nacionpad', 20)->default('');
                $table->string('estacivipad', 20)->default('');
                $table->string('domipad', 100)->default('');
                $table->string('cppad', 4)->default('');
                $table->string('ocupacpad', 30)->default('');
                $table->string('sitlabpad', 30)->default('');
                $table->string('lugtrapad', 30)->default('');
                $table->string('telepad', 50)->default('');
                $table->string('telecelpad', 50)->default('');
                $table->string('telltp', 50)->default('');
                $table->string('emailpad', 50)->default('');
                $table->string('nombretut', 50)->default('');
                $table->integer('dnitut')->nullable();
                $table->string('teletut', 20)->default('');
                $table->string('emailtut', 50)->default('');
                $table->string('ocupactut', 50)->default('');
                $table->string('lugtratut', 50)->default('');
                $table->string('telltt', 30)->default('');
                $table->string('respAdmiNom', 100)->default('');
                $table->integer('respAdmiDni')->default(0);
                $table->string('escori', 50)->default('');
                $table->string('destino', 50)->default('');
                $table->string('emeravis', 250)->default('');
                $table->string('retira', 250)->default('');
                $table->string('retira1', 250)->default('');
                $table->string('retira2', 250)->default('');
                $table->text('obs')->nullable();
                $table->dateTime('fechhora')->nullable();
                $table->string('identif', 100)->default('');
                $table->integer('idnivel')->default(0);
                $table->string('needes', 5)->default('');
                $table->text('needes_detalle')->nullable();
                $table->text('certDisc')->nullable();
                $table->string('vivecon', 200)->default('');
                $table->text('hermanos')->nullable();
                $table->string('ec_padres', 30)->nullable();
                $table->string('contacto1', 200)->nullable();
                $table->string('contacto2', 200)->nullable();
                $table->string('contacto3', 200)->nullable();
                $table->string('parroquia', 50)->default('');
                $table->text('motivo_detalle')->nullable();
                $table->string('acopro', 5)->default('');
                $table->text('acopro_detalle')->nullable();
                $table->boolean('bloqmatr')->default(false);
                $table->boolean('bloqadmi')->default(false);
                $table->dateTime('fechActDatos')->nullable();
                $table->string('libro', 10)->nullable();
                $table->string('folio', 10)->nullable();
                $table->string('legajo', 10)->default('');
                $table->text('obs_web')->nullable();
                $table->string('pwrd', 10)->default('');
                $table->string('reglamApenom', 100)->nullable();
                $table->integer('reglamDni')->nullable();
                $table->string('reglamEmail', 70)->nullable();
            });
        }

        // ── matricula ────────────────────────────────────────────
        if (! Schema::hasTable('matricula')) {
            Schema::create('matricula', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('idTerlec')->nullable();
                $table->integer('idNivel')->nullable();
                $table->integer('idCursos')->nullable();
                $table->integer('idLegajos')->nullable();
                $table->integer('idCondiciones')->nullable();
                $table->string('obsMatr', 25)->nullable();
                $table->integer('idCuotasbecas')->nullable();
                $table->string('nroMatricula', 10)->nullable();
                $table->date('fechaMatricula')->nullable();
                $table->text('obsAnual')->nullable();
                $table->string('conducta1', 20)->nullable();
                $table->string('conducta2', 20)->nullable();
                $table->integer('acept1')->default(0);
                $table->integer('acept2')->default(0);
                $table->integer('acept3')->default(0);
                $table->integer('acept4')->default(0);
                $table->integer('inscripto')->default(0);
            });
        }

        // ── calificaciones ───────────────────────────────────────
        if (! Schema::hasTable('calificaciones')) {
            Schema::create('calificaciones', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('idLegajos')->nullable();
                $table->integer('idMatricula')->nullable();
                $table->integer('ord')->nullable();
                $table->integer('idTerlec')->nullable();
                $table->integer('idCursos')->nullable();
                $table->integer('idMaterias')->nullable();
                $table->integer('idMatPlan')->nullable();
                // ic01..ic40
                for ($i = 1; $i <= 40; $i++) {
                    $col = 'ic' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $table->string($col, 15)->default('');
                }
                $table->text('obs01')->nullable();
                $table->text('obs02')->nullable();
                // tm1..tm6
                for ($i = 1; $i <= 6; $i++) {
                    $table->string("tm{$i}", 15)->default('');
                }
                $table->string('tmNota', 15)->default('');
                $table->string('dic', 10)->default('');
                $table->string('feb', 10)->default('');
                $table->integer('inscri')->default(0);
                $table->string('condAdeuda', 2)->nullable();
                $table->integer('apro')->nullable();
                $table->string('calif', 5)->nullable();
                $table->integer('mes')->nullable();
                $table->integer('ano')->nullable();
                $table->string('cond', 20)->nullable();
                $table->string('escuapro', 100)->nullable();
                $table->string('libro', 10)->nullable();
                $table->string('folio', 10)->nullable();
                $table->date('fechApro')->nullable();
                $table->string('libroDic', 10)->nullable();
                $table->string('folioDic', 10)->nullable();
                $table->date('fechAproDic')->nullable();
                $table->string('libroFeb', 10)->nullable();
                $table->string('folioFeb', 10)->nullable();
                $table->date('fechAproFeb')->nullable();
            });
        }

        // ── ento ─────────────────────────────────────────────────
        if (! Schema::hasTable('ento')) {
            Schema::create('ento', function (Blueprint $table) {
                $table->integer('Id')->autoIncrement();
                $table->integer('idNivel')->nullable();
                $table->integer('idTerlecVerNotas')->nullable();
                $table->integer('idTerlecVerNotas2')->nullable();
                $table->string('insti', 255)->nullable();
                $table->string('cue', 20)->nullable();
                $table->string('ee', 10)->nullable();
                $table->string('cuit', 14)->nullable();
                $table->string('categoria', 30)->nullable();
                $table->string('direccion', 100)->nullable();
                $table->string('localidad', 100)->nullable();
                $table->string('departamento', 50)->nullable();
                $table->string('provincia', 50)->nullable();
                $table->string('telefono', 30)->nullable();
                $table->string('mail', 50)->nullable();
                $table->string('replegal', 100)->nullable();
                $table->integer('idAspiTerlec')->nullable();
                $table->integer('platOff')->default(0);
                $table->text('offMensaje')->nullable();
                $table->integer('cargaNotasOff')->default(0);
                $table->text('notasOffMensaje')->nullable();
                $table->integer('verNotasOff')->default(0);
                $table->text('verOffMensaje')->nullable();
                $table->integer('actDatDocOff')->default(0);
                $table->string('environment', 50)->nullable();
                $table->integer('matriculaWebOff')->default(0);
                $table->text('mensajeBloqPeda')->nullable();
                $table->text('mensajeBloqAdmi')->nullable();
                $table->dateTime('FHinicioMatrWeb')->nullable();
                $table->integer('verLibreDeuda')->default(0);
                $table->string('apiDrive', 30)->nullable();
                $table->string('siroIniPrim', 20)->nullable();
                $table->string('siroSecu', 20)->nullable();
                $table->string('siroMje', 50)->nullable();
                $table->string('examTodosInscri', 1)->nullable();
                $table->integer('arancelesOff')->default(0);
                $table->string('documAcept1', 300)->nullable();
                $table->string('documAcept2', 300)->nullable();
                $table->string('documAcept3', 300)->nullable();
                $table->string('documAcept4', 300)->nullable();
                $table->string('claveCole', 50)->nullable();
            });
        }

        // ── profesortipo ──────────────────────────────────────────
        if (! Schema::hasTable('profesortipo')) {
            Schema::create('profesortipo', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->string('tipo', 30)->default('');
                $table->integer('accesoMenu')->default(0);
            });
        }

        // ── profesores ────────────────────────────────────────────
        if (! Schema::hasTable('profesores')) {
            Schema::create('profesores', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('IdTipoProf')->nullable();
                $table->string('apellido', 50)->default('');
                $table->string('nombre', 50)->default('');
                $table->string('dni', 15)->nullable();
                $table->string('cuil', 14)->default('');
                $table->string('sexo', 1)->default('');
                $table->integer('nivel')->default(0);
                $table->string('email', 100)->default('');
                $table->string('emailInsti', 100)->default('');
                $table->string('callenum', 100)->default('');
                $table->string('barrio', 60)->default('');
                $table->string('telefono', 30)->default('');
                $table->string('nacion', 30)->default('');
                $table->string('estacivi', 30)->default('');
                $table->string('legJunta', 30)->default('');
                $table->string('legEscuela', 30)->default('');
                $table->string('celular', 30)->default('');
                $table->date('fechnaci')->nullable();
                $table->string('titulo', 100)->default('');
                $table->string('numreg', 50)->default('');
                $table->string('apto', 10)->default('');
                $table->string('incapac', 10)->default('');
                $table->string('escalafonD', 30)->default('');
                $table->string('escalafonE', 30)->default('');
                $table->string('cargo', 100)->default('');
                $table->text('obs')->nullable();
                $table->integer('ult_idTerlec')->nullable();
                $table->integer('ult_idNivel')->nullable();
                $table->string('pwrd', 100)->default('');
                $table->string('permisos', 100)->default('');
            });
        }

        // ── permisosusuarios ──────────────────────────────────────
        if (! Schema::hasTable('permisosusuarios')) {
            Schema::create('permisosusuarios', function (Blueprint $table) {
                $table->integer('id')->autoIncrement();
                $table->integer('orden')->default(0);
                $table->string('tema', 100)->default('');
                $table->text('descripcion')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
        Schema::dropIfExists('matricula');
        Schema::dropIfExists('materias');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('matplan');
        Schema::dropIfExists('curplan');
        Schema::dropIfExists('planes');
        Schema::dropIfExists('condiciones');
        Schema::dropIfExists('legajos');
        Schema::dropIfExists('ento');
        Schema::dropIfExists('terlec');
        Schema::dropIfExists('niveles');
        Schema::dropIfExists('profesores');
        Schema::dropIfExists('profesortipo');
        Schema::dropIfExists('permisosusuarios');
    }
};
