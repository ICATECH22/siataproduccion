<?php

namespace App\Models\servicios;

use Illuminate\Database\Eloquent\Model;

class SolicitudServicio extends Model
{
    //
    protected $table = 'solicitudes';
    protected $primaryKey = 'id';
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';

    protected $fillable = [
        'idDepartamentoSolicitante',
        'idServicio',
        'descripcion',
        'idDepartamentoReceptora',
        'estatusSolicitud',
        'visto',
        'lector',
        'estatus',
        'fechaAlta',
        'fechaUMod',
        'fechaEliminacion',
        'idUsuarioAlta',
        'idUsuarioUMod',
        'idUsuarioEliminacion'
    ];
    
}
