<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'documento',
        'nombre',
        'correo',
        'tel_cel',
        'programas_id',
        'estado_estudiante',
        'direccion',
        'observaciones',
        
        
    ];


    public function convenios(){
        return $this->hasMany(Estudiante::class, 'id');
    }
    public function programas(){
        return $this->belongsTo(programa::class, 'programas_id');
    }
    
}
