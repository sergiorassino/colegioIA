<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfesorTipo extends Model
{
    use HasFactory;

    protected $table = 'profesortipo';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['tipo', 'accesoMenu'];

    public function profesores(): HasMany
    {
        return $this->hasMany(Profesor::class, 'IdTipoProf', 'id');
    }
}
