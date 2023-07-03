<?php

namespace App\Models\servicios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\servicios\HistorialServicios;

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

    /**
     * Get all of the comments for the Factura
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function historialservicio(): HasMany
    {
        return $this->hasMany(HistorialServicios::class, 'idSolicitud', 'id');
    }

}
