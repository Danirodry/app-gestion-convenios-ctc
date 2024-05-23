<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convenio extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresas_id',
        'estudiantes_id',
        'estado_convenio',
        'observaciones',
        'fecha_inicio',
        'fecha_fin',
        
    ];

    public function estudiantes(){
        return $this->belongsTo(estudiante::class, 'estudiantes_id');
    }

    public function empresas(){
        return $this->belongsTo(empresa::class, 'empresas_id');
    }
}
