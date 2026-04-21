<?php

namespace App\Auth;

use App\Models\Auth\PermisoUsuario;
use Illuminate\Support\Str;

class PermisoSlugMap
{
    /** @var array<string, int> */
    private array $map = [];

    public function __construct()
    {
        try {
            PermisoUsuario::orderBy('orden')->each(function (PermisoUsuario $p) {
                $slug = $this->temaToSlug($p->tema);
                $this->map[$slug] = $p->orden;
            });
        } catch (\Exception) {
            // Silenciar en contextos sin BD (ej. artisan commands durante install)
        }
    }

    public function ordenPorSlug(string $slug): ?int
    {
        return $this->map[$slug] ?? null;
    }

    /** @return array<string, int> */
    public function all(): array
    {
        return $this->map;
    }

    private function temaToSlug(string $tema): string
    {
        return Str::slug(strtolower($tema), '.');
    }
}
