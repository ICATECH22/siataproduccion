<?php

namespace App\Models\catalogos;

use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    //
    protected $table = 'servicios';
    protected $primaryKey = 'idServicio';
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';

    protected $fillable = [
        'descripcion',
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
        return $this->belongsTo(Departamento::class,'idDepartamento', 'id');
    }
}
