<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Legajo extends Model
{
    use HasFactory;

    protected $table = 'legajos';

    protected $primaryKey = 'id';

    public $timestamps = false;

    /**
     * No incluir pwrd en fillable general.
     * El campo pwrd se maneja solo en contexto de cambio de contraseña.
     */
    protected $fillable = [
        // Personal
        'idFamilias', 'codigo', 'apellido', 'nombre', 'dni', 'cuil', 'tipoalumno',
        'fechnaci', 'ln_ciudad', 'ln_depto', 'ln_provincia', 'ln_pais',
        'sexo', 'nacion', 'callenum', 'barrio', 'localidad', 'codpos',
        'telefono', 'email', 'idnivel',
        'needes', 'needes_detalle', 'certDisc', 'vivecon', 'hermanos', 'ec_padres',
        'contacto1', 'contacto2', 'contacto3', 'parroquia', 'motivo_detalle',
        'acopro', 'acopro_detalle', 'bloqmatr', 'bloqadmi', 'fechActDatos',
        'libro', 'folio', 'legajo', 'obs_web', 'obs', 'fechhora', 'identif',
        'escori', 'destino', 'emeravis', 'retira', 'retira1', 'retira2',
        // Madre
        'nombremad', 'dnimad', 'vivemad', 'fechnacmad', 'nacionmad', 'estacivimad',
        'domimad', 'cpmad', 'ocupacmad', 'sitlabmad', 'lugtramad',
        'telemad', 'telecelmad', 'telltm', 'emailmad',
        // Padre
        'nombrepad', 'dnipad', 'vivepad', 'fechnacpad', 'nacionpad', 'estacivipad',
        'domipad', 'cppad', 'ocupacpad', 'sitlabpad', 'lugtrapad',
        'telepad', 'telecelpad', 'telltp', 'emailpad',
        // Tutor
        'nombretut', 'dnitut', 'teletut', 'emailtut', 'ocupactut', 'lugtratut', 'telltt',
        // Responsable administrativo
        'respAdmiNom', 'respAdmiDni',
        // Reglamento
        'reglamApenom', 'reglamDni', 'reglamEmail',
    ];

    protected $hidden = ['pwrd'];

    protected $casts = [
        'fechnaci'    => 'date',
        'fechnacmad'  => 'date',
        'fechnacpad'  => 'date',
        'fechActDatos' => 'datetime',
        'fechhora'    => 'datetime',
        'bloqmatr'    => 'boolean',
        'bloqadmi'    => 'boolean',
        'tipoalumno'  => 'integer',
        'idnivel'     => 'integer',
    ];

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'idnivel', 'id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'idLegajos', 'id');
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'idLegajos', 'id');
    }

    public function matriculaActual(int $terlecId): ?Matricula
    {
        return $this->matriculas()->where('idTerlec', $terlecId)->first();
    }
}
