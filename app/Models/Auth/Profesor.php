<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Profesor extends Authenticatable
{
    use HasFactory;

    protected $table = 'profesores';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * pwrd nunca aparece en respuestas ni logs.
     */
    protected $hidden = ['pwrd'];

    protected $fillable = [
        'IdTipoProf', 'apellido', 'nombre', 'dni', 'cuil', 'sexo', 'nivel',
        'email', 'emailInsti', 'callenum', 'barrio', 'telefono', 'nacion',
        'estacivi', 'legJunta', 'legEscuela', 'celular', 'fechnaci', 'titulo',
        'numreg', 'apto', 'incapac', 'escalafonD', 'escalafonE', 'cargo', 'obs',
        'ult_idTerlec', 'ult_idNivel', 'permisos',
    ];

    // Solo para uso interno de cambio de contraseña
    protected $guarded = ['id', 'pwrd'];

    protected $casts = [
        'fechnaci' => 'date',
        'apto'     => 'date',
    ];

    /**
     * Campo de contraseña para Laravel Auth.
     */
    public function getAuthPassword(): string
    {
        return $this->pwrd ?? '';
    }

    /**
     * Campo de username para Laravel Auth (lo que el usuario ingresa).
     * Devuelve 'id' porque Auth identifica por PK, no por campo de búsqueda.
     */
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    /**
     * Verifica si el staff tiene el permiso en la posición $orden del string permisos.
     */
    public function tienePermiso(int $orden): bool
    {
        return ($this->permisos[$orden] ?? '0') === '1';
    }

    /**
     * Devuelve true si el rol es Profesor/a (menú limitado).
     */
    public function esProfesor(): bool
    {
        return $this->tipo?->tipo === 'Profesor/a';
    }

    /**
     * Devuelve true si el rol no tiene acceso al menú (Sin Rol).
     */
    public function sinRol(): bool
    {
        return $this->tipo === null || $this->tipo?->tipo === 'Sin Rol';
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(ProfesorTipo::class, 'IdTipoProf', 'id');
    }
}
