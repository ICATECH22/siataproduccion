<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id';
    
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rol',
        'estatus',
        'idUsuarioAlta',
        'idUsuarioUMod',
        'idUsuarioEliminacion',
        'fechAlta',
        'fechaUMod',
        'fechaEliminacion'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'idRol');
    }

}
