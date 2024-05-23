<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nit',
        'n_convenio',
        'nombre',
        'tel_cel',
        'direccion',
        'correo',
        'representante_legal',
        'estado_empresa',
        'observaciones',
        'inicio_convenio',
        'fin_convenio',
    
    ];


    public function convenios(){
        return $this->hasMany(Empresa::class, 'id');
    }
}
