# Convenciones — SistemasEscolares

## Modelos Eloquent sobre tablas legacy

```php
// Plantilla obligatoria para todos los modelos sobre tablas legacy
class NombreModelo extends Model
{
    protected $table = 'nombre_tabla';
    protected $primaryKey = 'id';  // 'Id' para cursos y ento (mayúscula)
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    // Relaciones con FK/OwnerKey explícitas (camelCase legacy)
    public function terlec(): BelongsTo
    {
        return $this->belongsTo(Terlec::class, 'idTerlec', 'id');
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class, 'idCursos', 'Id');  // 'Id' para cursos
    }
}
```

## Namespaces

| Tipo | Namespace |
|---|---|
| Modelos núcleo | `App\Models\Core\` |
| Modelos auth | `App\Models\Auth\` |
| Auth/providers | `App\Auth\` |
| Middlewares | `App\Http\Middleware\` |
| Livewire auth | `App\Livewire\Auth\` |
| Livewire core | `App\Livewire\Core\` |
| Servicios | `App\Services\` |

## Nomenclatura

- **Modelos**: PascalCase singular: `Terlec`, `Nivel`, `Plan`, `CurPlan`, `MatPlan`, `Curso`, `Materia`, `Legajo`, `Matricula`, `Calificacion`, `Ento`
- **Servicios**: sufijo `Service`: `GenerarCursoService`, `MatricularAlumnoService`, `EntornoService`
- **Livewire**: PascalCase descriptivo: `AbmCiclosLectivos`, `CursosDelAnio`, `MatricularAlumno`
- **Vistas Livewire**: kebab-case en `resources/views/livewire/`: `abm-ciclos-lectivos.blade.php`
- **Gates**: slug `tema.accion` derivado de `permisosusuarios.tema`: `administracion.permisos`, `cursos.materias`

## Convenciones de campos

- FKs en camelCase (como en la BD): `idTerlec`, `idCursos`, `idLegajos`, `idMatPlan`
- PK especial: `cursos.Id` (mayúscula), `ento.Id` (mayúscula)
- Passwords: siempre `Hash::make()` para nuevas; nunca leer `pwrd` en logs ni respuestas

## Servicios transaccionales

Todos los servicios con efectos de escritura múltiple usan transacciones:

```php
public function ejecutar(array $datos): ModelType
{
    return DB::transaction(function () use ($datos) {
        // 1. Validar
        // 2. Crear registro principal
        // 3. Crear registros dependientes
        // 4. Devolver resultado
    });
}
```

## Livewire CRUD estándar

Cada ABM Livewire tiene:
- Lista paginada con filtros (búsqueda en tiempo real)
- Modal de alta/edición con validación
- Confirmación de baja con validación de dependencias
- Mensajes de estado (`$this->dispatch('toast', ...)`)
- `wire:loading` en botones
- Estados vacío/cargando/error explícitos

## Testing

- Framework: **Pest 3**
- Cada módulo tiene tests antes de marcar como completo
- Factories para todos los modelos del núcleo
- `DatabaseTransactions` por defecto (aislamiento)
- Helpers globales: `actingAsStaff()`, `actingAsAlumno()`, `conPermiso()`, `sinPermiso()`
- Correr `composer check` antes de commit

## Calificaciones

Toda lectura/escritura a la tabla `calificaciones` pasa obligatoriamente por `App\Services\CalificacionesAdapter`, con métodos:
- `paraInicial(Calificacion $c): array`
- `paraPrimario(Calificacion $c): array`
- `paraSecundario(Calificacion $c): array`

No se accede directamente a `ic01..ic40`, `tm1..tm6`, `dic`, `feb` fuera del adapter.
