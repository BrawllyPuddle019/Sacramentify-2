<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diocesi extends Model
{
    use HasFactory;
    
    protected $table = 'diocesis';
    protected $primaryKey = 'cve_diocesis';
    protected $fillable = ['nombre_diocesis', 'direccion_diocesis']; 
    public $timestamps = false;
    public $incrementing = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_diocesis';
    }

    // Accessor para compatibilidad
    public function getNombreAttribute()
    {
        return $this->nombre_diocesis;
    }

    public function parroquias()
    {
        return $this->hasMany(Parroquia::class, 'cve_diocesis');
    }

    public function obispos()
    {
        return $this->hasMany(Obispo::class, 'cve_diocesis');
    }

    public function obispo()
    {
        return $this->hasOne(Obispo::class, 'cve_diocesis');
    }

    public function sacerdotes()
    {
        return $this->hasMany(\App\Models\Sacerdote::class, 'cve_diocesis', 'cve_diocesis');
    }
}
