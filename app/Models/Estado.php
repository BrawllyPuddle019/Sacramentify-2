<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;

    protected $table = 'estados';
    protected $primaryKey = 'cve_estado';
    protected $fillable = ['cve_estado', 'nombre_estado'];
    public $timestamps = false;
    public $incrementing = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_estado';
    }

    // Accessor para compatibilidad
    public function getNombreAttribute()
    {
        return $this->nombre_estado;
    }

    
}
