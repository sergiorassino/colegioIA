<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Condicion extends Model
{
    use HasFactory;

    protected $table = 'condiciones';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = ['orden', 'condicion', 'proteg'];

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'idCondiciones', 'id');
    }
}
