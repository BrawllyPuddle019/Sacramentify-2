<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ermita extends Model
{
    use HasFactory;
    
    protected $table = 'ermitas';
    protected $primaryKey = 'cve_ermitas';
    protected $fillable = [
        'cve_ermitas',
        'cve_parroquia',
        'cve_municipio',
        'nombre_ermita',
        'direccion',
        'latitude',
        'longitude'
    ]; 
    public $timestamps = false;
    public $incrementing = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_ermitas';
    }

    // Accessor para mantener compatibilidad con las vistas
    public function getNombreAttribute()
    {
        return $this->nombre_ermita;
    }

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class, 'cve_parroquia');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'cve_municipio');
    }

    public function actas()
    {
        return $this->hasMany(Acta::class, 'cve_ermitas', 'cve_ermitas');
    }
}
