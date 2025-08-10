<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;
    
    protected $table = 'municipios';
    protected $primaryKey = 'cve_municipio';
    protected $fillable = ['cve_municipio', 'nombre_municipio', 'cve_estado'];
    public $timestamps = false;
    public $incrementing = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_municipio';
    }

    // Accessor para compatibilidad
    public function getNombreAttribute()
    {
        return $this->nombre_municipio;
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'cve_estado', 'cve_estado');
    }
}
