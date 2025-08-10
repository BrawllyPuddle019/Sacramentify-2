<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platica extends Model
{
    use HasFactory;
    
    protected $table = 'platicas';
    protected $primaryKey = 'id';
    protected $fillable = ['padre','madre','nombre','fecha']; 
    public $timestamps = true;

    public function personaPadre()
    {
        return $this->belongsTo(Persona::class, 'padre', 'cve_persona');
    }

    public function personaMadre()
    {
        return $this->belongsTo(Persona::class, 'madre', 'cve_persona');
    }
}
