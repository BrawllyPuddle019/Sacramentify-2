<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    use HasFactory;
    
    protected $table = 'parroquias';
    protected $primaryKey = 'cve_parroquia';
    protected $fillable = [
        'cve_diocesis',
        'cve_municipio',
        'nombre_parroquia',
        'direccion',
        'latitude',
        'longitude'
    ]; 
    public $timestamps = false;
    public $incrementing = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_parroquia';
    }

    // Accessor para compatibilidad
    public function getNombreAttribute()
    {
        return $this->nombre_parroquia;
    }
    
    public function diocesis()
    {
        return $this->belongsTo(Diocesi::class, 'cve_diocesis');
    }

    public function municipio()
    {
        return $this->belongsTo(Municipio::class, 'cve_municipio');
    }

    public function ermitas()
    {
        return $this->hasMany(Ermita::class, 'cve_parroquias');
    }
}