<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    //
    protected $table = 'unidad';
    protected $primaryKey = 'idUnidad';
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';


    protected $fillable = [
        'descripcion',
        'direccion',
        'telefono',
        'estatus',
        'fechaAlta',
        'fechaUMod',
        'fechaEliminacion',
        'idUsuarioAlta',
        'idUsuarioUMod',
        'idUsuarioEliminacion'
    ];
    public function organos()
    {
        return $this->hasMany(Organo::class, 'idUnidad');
    }

    // public function departamento() {
    //     return $this->hasOne(Estado::class, 'idEstado', 'idEstado');
    // }

    public function departamento() {
        return $this->hasOne(Estado::class, 'idEstado', 'idEstado');
    }
}
