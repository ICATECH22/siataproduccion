<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Model;

class DepartamentoServicios extends Model
{
    //
    protected $table = 'departamentoservicios';
    protected $primaryKey = 'id';
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';

    protected $fillable = [
        'idDepartamento',
        'idServicio',
        'estatus',
        'fechaAlta',
        'fechaUMod',
        'fechaEliminacion',
        'idUsuarioAlta',
        'idUsuarioUMod',
        'idUsuarioEliminacion'
    ];
}
