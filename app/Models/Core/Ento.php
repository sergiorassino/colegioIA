<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ento extends Model
{
    use HasFactory;

    protected $table = 'ento';

    /**
     * La PK en la BD es 'Id' con mayúscula.
     */
    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected $fillable = [
        'idNivel', 'idTerlecVerNotas', 'idTerlecVerNotas2', 'idAspiTerlec',
        // Datos institucionales
        'insti', 'cue', 'ee', 'cuit', 'categoria',
        'direccion', 'localidad', 'departamento', 'provincia',
        'telefono', 'mail', 'replegal',
        // Flags de bloqueo
        'platOff', 'offMensaje',
        'cargaNotasOff', 'notasOffMensaje',
        'verNotasOff', 'verOffMensaje',
        'actDatDocOff',
        'matriculaWebOff', 'mensajeBloqPeda', 'mensajeBloqAdmi',
        'FHinicioMatrWeb',
        'verLibreDeuda',
        'arancelesOff',
        // Documentos
        'documAcept1', 'documAcept2', 'documAcept3', 'documAcept4',
        // Integraciones
        'apiDrive', 'siroIniPrim', 'siroSecu', 'siroMje',
        'environment', 'claveCole',
        'examTodosInscri',
    ];

    protected $casts = [
        'idNivel'          => 'integer',
        'idTerlecVerNotas' => 'integer',
        'platOff'          => 'boolean',
        'cargaNotasOff'    => 'boolean',
        'verNotasOff'      => 'boolean',
        'actDatDocOff'     => 'boolean',
        'matriculaWebOff'  => 'boolean',
        'arancelesOff'     => 'boolean',
        'verLibreDeuda'    => 'boolean',
        'FHinicioMatrWeb'  => 'datetime',
    ];

    public function nivel(): BelongsTo
    {
        return $this->belongsTo(Nivel::class, 'idNivel', 'id');
    }

    public function terlecAutogesion(): BelongsTo
    {
        return $this->belongsTo(Terlec::class, 'idTerlecVerNotas', 'id');
    }

    public function terlecHistorico(): BelongsTo
    {
        return $this->belongsTo(Terlec::class, 'idTerlecVerNotas2', 'id');
    }

    public function terlecAspirantes(): BelongsTo
    {
        return $this->belongsTo(Terlec::class, 'idAspiTerlec', 'id');
    }

    public function estaActiva(): bool
    {
        return ! (bool) $this->platOff;
    }

    public function puedeVerNotas(): bool
    {
        return ! (bool) $this->verNotasOff;
    }

    public function puedeCargarNotas(): bool
    {
        return ! (bool) $this->cargaNotasOff;
    }

    public function puedeMatricularPorWeb(): bool
    {
        return ! (bool) $this->matriculaWebOff;
    }
}
