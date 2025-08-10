<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Sacerdote extends Model
{
    use HasFactory;

    protected $table = 'sacerdotes';
    protected $primaryKey = 'cve_sacerdotes';
    protected $fillable = ['cve_sacerdotes', 'nombre_sacerdote', 'apellido_paterno', 'apellido_materno', 'cve_diocesis'];
    public $timestamps = false;
    public $incrementing = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_sacerdotes';
    }

    // Relación con Diocesi
    public function diocesi()
    {
        return $this->belongsTo(\App\Models\Diocesi::class, 'cve_diocesis', 'cve_diocesis');
    }

    // Alias para compatibilidad
    public function diocesis()
    {
        return $this->diocesi();
    }
}