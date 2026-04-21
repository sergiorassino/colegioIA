<?php

namespace App\Auth;

use App\Models\Auth\Profesor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;

class PermisosService
{
    public function __construct(private readonly PermisoSlugMap $slugMap) {}

    /**
     * Registra un Gate por cada permiso en permisosusuarios.
     * Llamar desde AppServiceProvider::boot().
     */
    public function registrarGates(): void
    {
        foreach ($this->slugMap->all() as $slug => $orden) {
            Gate::define($slug, function (Authenticatable $user) use ($orden) {
                if (! $user instanceof Profesor) {
                    return false;
                }

                return $user->tienePermiso($orden);
            });
        }
    }
}
