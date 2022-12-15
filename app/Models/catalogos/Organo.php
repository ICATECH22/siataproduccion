<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Model;

class Organo extends Model
{
    //
    protected $table = 'organo';
    protected $primaryKey = 'id';
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';


    protected $fillable = [
        'idUnidad',
        'organo',
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
    public function departamentos()
    {
        return $this->hasMany(Departamento::class, 'idOrgano');
    }
    public function servicios()
    {
        return $this->hasMany(Servicios::class, 'idDepartamento');
    }
     public function usuarios()
    {
        return $this->hasMany(User::class, 'idUsuario');
    }
}

