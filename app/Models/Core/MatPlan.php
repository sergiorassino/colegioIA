<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatPlan extends Model
{
    use HasFactory;

    protected $table = 'matplan';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'idCurPlan', 'matPlanMateria', 'ord', 'abrev', 'codGE', 'codGE2', 'codGE3',
    ];

    protected $casts = [
        'ord' => 'integer',
    ];

    public function curPlan(): BelongsTo
    {
        return $this->belongsTo(CurPlan::class, 'idCurPlan', 'id');
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class, 'idMatPlan', 'id');
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'idMatPlan', 'id');
    }
}
