<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obispo extends Model
{
    use HasFactory;

    protected $table = 'obispos';
    protected $primaryKey = 'cve_obispos';
    protected $fillable = ['cve_obispos', 'nombre_obispo', 'apellido_paterno', 'apellido_materno', 'cve_diocesis'];
    public $timestamps = false;
    public $incrementing = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_obispos';
    }

    // Accessor para compatibilidad
    public function getNombreAttribute()
    {
        return $this->nombre_obispo;
    }

    public function getPaterno()
    {
        return $this->apellido_paterno;
    }

    public function getMaterno()
    {
        return $this->apellido_materno;
    }

    public function diocesi()
    {
        return $this->belongsTo(Diocesi::class, 'cve_diocesis');
    }
}