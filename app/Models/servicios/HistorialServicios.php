<?php

namespace App\Models\servicios;

use App\Models\catalogos\Departamento;
use App\Models\servicios\SolicitudServicio;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialServicios extends Model
{
    //
    protected $table = 'historialsolicitudes';
    protected $primaryKey = 'id';
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';

    protected $fillable = [
        'idSolicitud',
        'idServicio',
        'descripcion',
        'idDepartamentoReceptora',
        'estatusSolicitud',
        'motivo',
        'estatus',
        'fechaAlta',
        'fechaUMod',
        'fechaEliminacion',
        'idUsuarioAlta',
        'idUsuarioUMod',
        'idUsuarioEliminacion'
    ];



    public function usuario()
    {
        return $this->hasMany(User::class, 'idUsuarioAlta');
    }

    public function solicitudes(): BelongsTo
    {
        return $this->belongsTo(SolicitudServicio::class, 'idSolicitud', 'id');
    }

}
