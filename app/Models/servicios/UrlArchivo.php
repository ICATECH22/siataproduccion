<?php

namespace App\Models\servicios;

use Illuminate\Database\Eloquent\Model;

class UrlArchivo extends Model
{
    //
    protected $table = 'archivos';
    protected $primaryKey = 'id';
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';

    protected $fillable = [
        'idSolicitud',
        'tipoArchivo',
        'urlArchivo',
        'nombreArchivo',
        'estatus',
        'fechaAlta',
        'fechaUMod',
        'fechaEliminacion',
        'idUsuarioAlta',
        'idUsuarioUMod',
        'idUsuarioEliminacion'
    ];
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDepartamento');
    }
}
