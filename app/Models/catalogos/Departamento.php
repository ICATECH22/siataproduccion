<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    //
    protected $table = 'departamento';
    protected $primaryKey = 'id';
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';


    protected $fillable = [
        'idOrgano',
        'departamento',
        'titular',
        'correo',
        'telefono',
        'celular',
        'estatus',
        'fechaAlta',
        'fechaUMod',
        'fechaEliminacion',
        'idUsuarioAlta',
        'idUsuarioUMod',
        'idUsuarioEliminacion'
    ];

    public function organo()
    {
        return $this->belongsTo(Organo::class, 'idOrgano');
    }

    public function servicios()
    {
        return $this->hasMany(Servicios::class, 'idDepartamento');
    }

    public function usuarios()
    {
        return $this->hasMany(User::class, 'idUsuario');
    }
    // public function departamento() {
    //     return $this->hasOne(Estado::class, 'idEstado', 'idEstado');
    // }
}
