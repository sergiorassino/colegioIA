<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Terlec extends Model
{
    use HasFactory;

    protected $table = 'terlec';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['ano', 'orden'];

    protected $casts = [
        'ano'   => 'integer',
        'orden' => 'integer',
    ];

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class, 'idTerlec', 'id');
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class, 'idTerlec', 'id');
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'idTerlec', 'id');
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class, 'idTerlec', 'id');
    }

    public function entos(): HasMany
    {
        return $this->hasMany(Ento::class, 'idTerlecVerNotas', 'id');
    }

    public function scopeActivo($query)
    {
        return $query->orderBy('orden')->limit(1);
    }
}
