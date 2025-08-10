<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'actas';
    protected $primaryKey = 'cve_actas';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'cve_persona',
        'cve_persona2',
        'Per_cve_padrino1',
        'Per_cve_madrina1',
        'cve_ermitas',
        'cve_sacerdotes_celebrante',
        'cve_sacerdotes_asistente',
        'cve_obispos_celebrante',
        'Per_cve_padrino',
        'Per_cve_madrina',
        'Per_cve_padre',
        'Per_cve_madre1',
        'fecha',
        'Per_cve_madre',
        'Per_cve_padre1',
        'Libro',
        'Fojas',
        'Folio',
        'tipo_acta',
        'numero_consecutivo', // Agregar el nuevo campo
    ];
    
    protected $dates = ['deleted_at']; // Para soft deletes
    public $timestamps = false;

    // Relaciones
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cve_persona', 'cve_persona');
    }
    public function persona2()
    {
        return $this->belongsTo(Persona::class, 'cve_persona2', 'cve_persona');
    }
    public function padrino1()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_padrino1', 'cve_persona');
    }
    public function madrina1()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_madrina1', 'cve_persona');
    }
    public function ermita()
    {
        return $this->belongsTo(Ermita::class, 'cve_ermitas', 'cve_ermitas');
    }
    public function sacerdoteCelebrante()
    {
        return $this->belongsTo(Sacerdote::class, 'cve_sacerdotes_celebrante', 'cve_sacerdotes');
    }
    public function sacerdoteAsistente()
    {
        return $this->belongsTo(Sacerdote::class, 'cve_sacerdotes_asistente', 'cve_sacerdotes');
    }
    public function obispoCelebrante()
    {
        return $this->belongsTo(Obispo::class, 'cve_obispos_celebrante', 'cve_obispos');
    }
    public function padrino()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_padrino', 'cve_persona');
    }
    public function madrina()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_madrina', 'cve_persona');
    }
    public function padre()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_padre', 'cve_persona');
    }
    public function madre1()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_madre1', 'cve_persona');
    }
    public function madre()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_madre', 'cve_persona');
    }
    public function padre1()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_padre1', 'cve_persona');
    }
    public function tipoActa()
    {
        return $this->belongsTo(Sacramento::class, 'tipo_acta', 'cve_sacramentos');
    }
    
    // Alias para compatibilidad
    public function sacramento()
    {
        return $this->tipoActa();
    }

    // Método auxiliar para obtener personas por tipo (simulando relación many-to-many)
    public function getPersonasPorTipo()
    {
        $personas = collect();
        
        // Determinar el tipo de sacramento y asignar roles
        if ($this->tipoActa) {
            $tipoSacramento = strtolower($this->tipoActa->nombre);
            
            if ($tipoSacramento == 'matrimonio') {
                if ($this->persona) {
                    $personas->push((object)[
                        'persona' => $this->persona,
                        'tipo_persona' => 'Esposo'
                    ]);
                }
                if ($this->persona2) {
                    $personas->push((object)[
                        'persona' => $this->persona2,
                        'tipo_persona' => 'Esposa'
                    ]);
                }
                if ($this->padrino1) {
                    $personas->push((object)[
                        'persona' => $this->padrino1,
                        'tipo_persona' => 'Testigo 1'
                    ]);
                }
                if ($this->madrina1) {
                    $personas->push((object)[
                        'persona' => $this->madrina1,
                        'tipo_persona' => 'Testigo 2'
                    ]);
                }
            } elseif ($tipoSacramento == 'bautizo') {
                if ($this->persona) {
                    $personas->push((object)[
                        'persona' => $this->persona,
                        'tipo_persona' => 'Bautizado'
                    ]);
                }
                if ($this->padrino) {
                    $personas->push((object)[
                        'persona' => $this->padrino,
                        'tipo_persona' => 'Padrino'
                    ]);
                }
                if ($this->madrina) {
                    $personas->push((object)[
                        'persona' => $this->madrina,
                        'tipo_persona' => 'Madrina'
                    ]);
                }
            } elseif ($tipoSacramento == 'confirmacion') {
                if ($this->persona) {
                    $personas->push((object)[
                        'persona' => $this->persona,
                        'tipo_persona' => 'Confirmado'
                    ]);
                }
                if ($this->padrino) {
                    $personas->push((object)[
                        'persona' => $this->padrino,
                        'tipo_persona' => 'Padrino Confirmacion'
                    ]);
                }
                if ($this->madrina) {
                    $personas->push((object)[
                        'persona' => $this->madrina,
                        'tipo_persona' => 'Madrina Confirmacion'
                    ]);
                }
            }
        }
        
        return $personas;
    }
}

