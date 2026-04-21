<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermisosUsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            [0,  '***ADMINISTRACION / PERMISOS***',              'Administrar permisos de usuarios'],
            [1,  'ADMINISTRACIÓN',                               'Administración de cursos y materias del año'],
            [2,  'LEGAJOS DEL ALUMNO',                          'Botón INSCRIBIR en legajo'],
            [3,  'LEGAJOS DEL ALUMNO',                          'Botón NUEVO ESTUDIANTE en legajo'],
            [4,  'CALIFICACIONES / CARGA DE NOTAS',             'Acceso a carga de calificaciones'],
            [5,  'LEGAJOS DEL ALUMNO',                          'Botón ACTUALIZAR DATOS en legajo'],
            [6,  'LEGAJOS DEL ALUMNO',                          'Botón BORRAR en legajo'],
            [7,  'SANCIONES',                                   'Registrar nueva sanción'],
            [8,  'SANCIONES',                                   'Modificar sanción existente'],
            [9,  'SANCIONES',                                   'Borrar sanción existente'],
            [10, 'ADMINISTRACIÓN',                              'Administración de cursos y materias modelo'],
            [11, 'INASISTENCIAS',                               'Carga de NUEVAS inasistencias'],
            [12, 'INASISTENCIAS',                               'MODIFICACIÓN de asistencias existentes'],
            [13, 'INASISTENCIAS',                               'ELIMINACIÓN de inasistencias existentes'],
            [14, 'EXÁMENES',                                    'Acceso al módulo de CITACIONES A PROFESORES'],
            [15, 'EXÁMENES',                                    'Acceso a CARGA MANUAL / INSCRIPCIONES a examen'],
            [16, 'EXÁMENES',                                    'BORRAR TODAS LAS INSCRIPCIONES A EXAMEN'],
            [17, 'EXÁMENES',                                    'Imprimir LISTADO DE ASIGNATURAS ADEUDADAS'],
            [18, 'EXÁMENES',                                    'Imprimir ACTAS VOLANTES DE EXAMEN'],
            [19, 'EXÁMENES',                                    'Módulo de PERMISOS DE EXAMEN'],
            [20, 'MATRÍZ / ANALÍTICO',                         'Libro Matrí­z e impresión del Certificado Analítico'],
            [21, 'DATOS DEL COLEGIO',                           'Editar datos de la Institución y entorno'],
            [22, 'PLANIFICACIONES Y PROGRAMAS',                 'Aceptar planificaciones y programas de profesores'],
            [23, 'CALIFICACIONES / CARGA DE NOTAS',             'GUARDAR CAMBIOS en calificaciones'],
            [24, 'ADMINISTRACIÓN / CALIFICACIONES / CIERRE ANUAL','Acceso al módulo de CIERRE ANUAL'],
            [25, 'IEF / GESTIÓN CONTENIDOS Y APRENDIZAJES',    'Gestión de Contenidos y Aprendizajes'],
            [26, 'IEF / CARGAR',                               'Carga de I.E.F.'],
            [27, 'INASISTENCIAS / TOMAR LISTA',                'Módulo TOMAR LISTA'],
            [28, 'PROFESORES / LEGAJO DEL DOCENTE',            'Modificar datos en Legajo del Docente'],
            [29, 'PROFESORES / ASIGNACIÓN POR CURSO',          'Asignación de Profesores por Curso'],
            [30, 'ADMINISTRACIÓN / DATOS DEL COLEGIO',         'Carga de datos de la Institución'],
            [31, 'ADMINISTRACIÓN / GESTIÓN AYUDA',             'Gestión de Pantallas de Ayuda'],
            [32, 'ADMINISTRACIÓN / COPIAR CUR. Y MAT',         'Copiar Cursos y Materias de un año a otro'],
            [33, 'ADMINISTRACIÓN / PROMOVER ALUMNOS',          'Promover alumnos al curso subsiguiente'],
            [34, 'ADMINISTRACIÓN / PASAR ASIGNACIONES',        'Pasar asignaciones de profesores de un año a otro'],
            [35, 'PROFESORES / LIBRO DE TEMAS',                'Editar fecha bloqueada en Libro de Temas'],
            [36, 'CUOTAS / CREAR, EDITAR, BORRAR',             'Creación y gestión de cuotas'],
            [37, 'CUOTAS / EDITAR IMPORTES',                   'Edición de importes de cuotas por curso'],
            [38, 'CUOTAS / GENERAR MASIVAMENTE',               'Generación masiva de cuotas a alumnos'],
            [39, 'CUOTAS / ELIMINAR MASIVAMENTE',              'Eliminación masiva de cuotas generadas'],
            [40, 'CUOTAS / EDICIÓN MASIVA',                    'Edición masiva de cuotas ya generadas'],
            [41, 'CUOTAS / TIPOS DE BECA',                     'Creación o edición de tipos de Beca'],
            [42, 'CUOTAS / ASIGNACIÓN DE BECAS',               'Asignar becas a alumnos'],
            [43, 'MATRÍCULA WEB / BLOQUEO MASIVO',             'Bloqueo masivo para matrícula web'],
            [44, 'REINCORPORACIONES / OTORGAR',                'Editar campos de Reincorporación'],
            [45, 'HORARIOS / CARGA',                           'Carga de horarios docentes'],
            [46, 'PROFESORES / INASISTENCIAS DOCENTES',        'Gestión de Inasistencias Docentes'],
            [47, 'EXÁMENES - TERCER MATERIA',                  'Gestión de Tercer Materia'],
            [48, 'PROFESORES / COMUNICACIONES A DOCENTES',     'Edición de Comunicaciones a Docentes'],
            [49, 'GESTIÓN DE ASPIRANTES',                      'Gestión de Aspirantes a vacantes'],
        ];

        foreach ($permisos as $idx => [$orden, $tema, $descripcion]) {
            DB::table('permisosusuarios')->insertOrIgnore([
                'id'          => $idx + 1,
                'orden'       => $orden,
                'tema'        => $tema,
                'descripcion' => $descripcion,
            ]);
        }
    }
}
