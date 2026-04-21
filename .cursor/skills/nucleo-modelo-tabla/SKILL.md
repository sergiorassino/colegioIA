# Skill: Crear modelo Eloquent sobre tabla legacy

## Cuándo usar

Al crear un nuevo modelo Eloquent sobre una tabla del schema MySQL existente.

## Checklist

1. Verificar la estructura de la tabla en `schema.sql` o `docs/modelo-datos.md`
2. Identificar:
   - Nombre de la tabla (`$table`)
   - Nombre del PK (`$primaryKey`) — ¿es `id` o `Id` con mayúscula?
   - Si tiene `AUTO_INCREMENT` (`$incrementing`)
   - Si tiene timestamps (`$timestamps = false` siempre para tablas legacy)
   - FKs (nombres en camelCase)
3. Crear el modelo en el namespace correcto (`App\Models\Core\` o `App\Models\Auth\`)
4. Declarar `$fillable` con los campos editables
5. Agregar relaciones Eloquent con FK/OwnerKey explícitas
6. Crear la factory correspondiente en `database/factories/`

## Plantilla

```php
<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NombreModelo extends Model
{
    use HasFactory;

    protected $table = 'nombre_tabla';

    // ↓ Si la PK es 'Id' (mayúscula, solo en cursos y ento)
    // protected $primaryKey = 'Id';

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;  // SIEMPRE false en tablas legacy

    protected $fillable = [
        // Listar campos editables (NO incluir PK)
    ];

    // Relaciones (FKs en camelCase como en la BD)
    public function relacionN(): BelongsTo
    {
        return $this->belongsTo(OtroModelo::class, 'idOtro', 'id');
    }

    public function hijos(): HasMany
    {
        return $this->hasMany(OtroModelo::class, 'idEsteModelo', 'id');
    }
}
```

## Casos especiales conocidos

| Tabla | PK | Nota |
|---|---|---|
| `cursos` | `Id` | `protected $primaryKey = 'Id'` |
| `ento` | `Id` | `protected $primaryKey = 'Id'` |
| `terlec` | `id` | Usa `KEY id` sin `PRIMARY KEY`, funciona igual |
| `condiciones` | `id` | Igual |
| `profesortipo` | `id` | Igual |
| `legajos` | `id` | `UNIQUE KEY dni (dni)` → se puede usar `firstWhere('dni', $dni)` |

## Relaciones con cursos (PK mayúscula)

```php
// Desde materias → cursos
public function curso(): BelongsTo
{
    return $this->belongsTo(Curso::class, 'idCursos', 'Id');
}

// Desde cursos → materias
public function materias(): HasMany
{
    return $this->hasMany(Materia::class, 'idCursos', 'Id');
}
```

## Factory correspondiente

```php
<?php

namespace Database\Factories\Core;

use App\Models\Core\NombreModelo;
use App\Models\Core\Terlec;
use Illuminate\Database\Eloquent\Factories\Factory;

class NombreModeloFactory extends Factory
{
    protected $model = NombreModelo::class;

    public function definition(): array
    {
        return [
            'campo1' => $this->faker->word(),
            'idTerlec' => Terlec::factory(),
            // ...
        ];
    }

    // Estados útiles
    public function activo(): static
    {
        return $this->state(['orden' => 1]);
    }
}
```
