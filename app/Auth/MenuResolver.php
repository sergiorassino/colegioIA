<?php

namespace App\Auth;

use App\Models\Auth\Profesor;
use Illuminate\Support\Facades\Gate;

class MenuResolver
{
    /**
     * Devuelve la estructura del menú para el staff autenticado.
     *
     * @param Profesor $profesor
     * @return array<int, array{label: string, icon: string, route: string, visible: bool}>
     */
    public function resolver(Profesor $profesor): array
    {
        if ($profesor->sinRol()) {
            return [];
        }

        if ($profesor->esProfesor()) {
            return $this->menuProfesor();
        }

        return $this->menuCompleto($profesor);
    }

    /**
     * Menú limitado para rol Profesor/a.
     */
    private function menuProfesor(): array
    {
        return [
            ['label' => 'Mis cursos',            'icon' => 'book-open',   'route' => 'staff.mis-cursos',   'visible' => true],
            ['label' => 'Libro de temas',         'icon' => 'notebook',    'route' => 'staff.libro-temas',  'visible' => true],
            ['label' => 'Mis calificaciones',     'icon' => 'clipboard',   'route' => 'staff.mis-calif',    'visible' => true],
            ['label' => 'Mis datos',              'icon' => 'user',        'route' => 'staff.mis-datos',    'visible' => true],
            ['label' => 'Cerrar sesión',          'icon' => 'log-out',     'route' => 'staff.logout',       'visible' => true],
        ];
    }

    /**
     * Menú completo para otros roles, filtrado por permisos.
     */
    private function menuCompleto(Profesor $profesor): array
    {
        $items = [
            // Núcleo
            [
                'label'   => 'Ciclos lectivos',
                'icon'    => 'calendar',
                'route'   => 'staff.terlec.index',
                'visible' => true,
            ],
            [
                'label'   => 'Niveles',
                'icon'    => 'layers',
                'route'   => 'staff.nivel.index',
                'visible' => true,
            ],
            [
                'label'   => 'Planes de estudio',
                'icon'    => 'book',
                'route'   => 'staff.plan.index',
                'visible' => true,
            ],
            [
                'label'   => 'Cursos modelo',
                'icon'    => 'layout-grid',
                'route'   => 'staff.curplan.index',
                'visible' => true,
            ],
            [
                'label'   => 'Entorno institucional',
                'icon'    => 'building',
                'route'   => 'staff.ento.index',
                'visible' => true,
            ],
            [
                'label'   => 'Cursos del año',
                'icon'    => 'calendar-days',
                'route'   => 'staff.cursos.index',
                'visible' => true,
            ],
            [
                'label'   => 'Legajos de alumnos',
                'icon'    => 'users',
                'route'   => 'staff.legajos.index',
                'visible' => true,
            ],
            [
                'label'   => 'Matriculación',
                'icon'    => 'user-plus',
                'route'   => 'staff.matriculas.index',
                'visible' => true,
            ],
            // Permisos/Administración (solo si tiene permiso orden=0)
            [
                'label'   => 'Permisos de usuario',
                'icon'    => 'shield',
                'route'   => 'staff.permisos',
                'visible' => Gate::forUser($profesor)->allows('administracion.permisos'),
            ],
        ];

        return array_filter($items, fn ($item) => $item['visible']);
    }
}
