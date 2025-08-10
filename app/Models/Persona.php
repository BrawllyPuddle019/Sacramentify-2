<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $table = 'personas';
    protected $primaryKey = 'cve_persona'; 
    protected $fillable = ['cve_persona', 'cve_municipio','nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento', 'sexo', 'direccion','telefono'];
    public $timestamps = false;
    public $incrementing = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_persona';
    }

    public function municipio(){
        return $this->belongsTo(Municipio::class,'cve_municipio', 'cve_municipio');
    }

    // Alias para compatibilidad
    public function municipios(){
        return $this->municipio();
    }

    // Accessors para compatibilidad con nombres de campo legacy
    public function getPaternoAttribute()
    {
        return $this->apellido_paterno;
    }

    public function getMaternoAttribute()
    {
        return $this->apellido_materno;
    }

    // Relación con estado a través del municipio
    public function estado(){
        return $this->hasOneThrough(
            Estado::class,           // Modelo destino
            Municipio::class,        // Modelo intermedio  
            'cve_municipio',         // Foreign key en tabla intermedia que se relaciona con esta tabla
            'cve_estado',            // Foreign key en tabla destino que se relaciona con tabla intermedia
            'cve_municipio',         // Local key en esta tabla
            'cve_estado'             // Local key en tabla intermedia
        );
    }
}
