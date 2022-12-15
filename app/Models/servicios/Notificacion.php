<?php

namespace App\Models\servicios;

use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    //
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'idSolicitud',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at',
        'read_movil',
    ];
}


