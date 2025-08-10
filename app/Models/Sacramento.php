<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sacramento extends Model
{
    use HasFactory;
    
    protected $table = 'sacramentos';
    protected $primaryKey = 'cve_sacramentos';
    protected $fillable = ['nombre_sacramento', 'descripcion']; 
    public $timestamps = false;

    // Configurar route model binding
    public function getRouteKeyName()
    {
        return 'cve_sacramentos';
    }

    // Accessor para compatibilidad
    public function getNombreAttribute()
    {
        return $this->nombre_sacramento;
    }

     public function actas()
    {
        return $this->hasMany(Acta::class, 'tipo_acta', 'cve_sacramentos');
    }
    
}
