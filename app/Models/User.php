<?php

namespace App\Models;

use App\Models\catalogos\Departamento;
use App\Models\catalogos\Departamento2;
use App\Models\catalogos\Organo;
use App\Models\catalogos\Unidad;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'users';
    protected $primaryKey = 'idUsuario';
    
    const CREATED_AT = 'fechaAlta';
    const UPDATED_AT = 'fechaUMod';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idOrganoDepartamento',
        'name',
        'email',
        'password',
        'idRol',
        'estatus',
        'fechAlta',
        'fechaUMod',
        'fechaEliminacion',
        'idUsuarioAlta',
        'idUsuarioUMod',
        'idUsuarioEliminacion'
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    public function rol(){
        return $this->belongsTo(Rol::class, 'idRol','id');
    }

    public function organo()
    {
        return $this->belongsTo(Organo::class,'idOrganoDepartamento', 'id');
    }
    public function departamento()
    {
        return $this->belongsTo(Departamento::class,'idOrganoDepartamento', 'id');
    }
}
