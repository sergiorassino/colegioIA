# Skill: Permisos de staff (Gates por bit de permisos)

## Contexto

Los permisos están en `profesores.permisos` (VARCHAR 100), donde cada posición del string
corresponde al `permisosusuarios.orden` (0..49). `'1'` = tiene permiso, `'0'` = no tiene.

## PermisoSlugMap

Construye el mapa `slug → orden` leyendo `permisosusuarios`:

```php
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
        PermisoUsuario::orderBy('orden')->get()->each(function ($p) {
            $slug = Str::slug($p->tema, '.');
            $this->map[$slug] = $p->orden;
        });
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
}
```

## PermisosService — registra Gates

```php
<?php

namespace App\Auth;

use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\Auth\Profesor;

class PermisosService
{
    public function __construct(private readonly PermisoSlugMap $slugMap) {}

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
```

## Registrar en AuthServiceProvider (o AppServiceProvider)

```php
// En boot()
app(PermisosService::class)->registrarGates();
```

## Agregar un permiso nuevo

1. Insertar en `permisosusuarios`:
   ```sql
   INSERT INTO permisosusuarios (orden, tema, descripcion)
   VALUES (50, 'NUEVO_MODULO', 'Descripción del nuevo permiso');
   ```

2. El slug generado automáticamente sería: `nuevo.modulo`

3. Usar en código:
   ```php
   Gate::allows('nuevo.modulo')         // en PHP
   @can('nuevo.modulo')                 // en Blade
   EnsurePermiso:nuevo.modulo           // en middleware
   ```

4. Para admin: dar acceso poniendo `'1'` en la posición 50 del string `permisos`.

## Middleware EnsurePermiso

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class EnsurePermiso
{
    public function handle(Request $request, Closure $next, string $slug): mixed
    {
        if (! Auth::guard('staff')->check()) {
            return redirect()->route('staff.login');
        }

        if (! Gate::allows($slug)) {
            abort(403, 'No tiene permiso para acceder a este módulo.');
        }

        return $next($request);
    }
}
```

## Helper en Profesor::tienePermiso

```php
// En App\Models\Auth\Profesor
public function tienePermiso(int $orden): bool
{
    return ($this->permisos[$orden] ?? '0') === '1';
}
```

## Tests requeridos

```php
it('PermisoSlugMap construye el mapa desde permisosusuarios', function () {
    PermisoUsuario::factory()->create(['orden' => 0, 'tema' => 'ADMINISTRACION / PERMISOS']);
    $map = new PermisoSlugMap();
    expect($map->ordenPorSlug('administracion-permisos'))->toBe(0);
});

it('Gate concede si el bit es 1', function () {
    $profesor = Profesor::factory()->create(['permisos' => '1' . str_repeat('0', 49)]);
    actingAsStaff($profesor, 1, 1);
    expect(Gate::allows('administracion.permisos'))->toBeTrue();
});

it('Gate deniega si el bit es 0', function () {
    $profesor = Profesor::factory()->create(['permisos' => '0' . str_repeat('0', 49)]);
    actingAsStaff($profesor, 1, 1);
    expect(Gate::allows('administracion.permisos'))->toBeFalse();
});
```
