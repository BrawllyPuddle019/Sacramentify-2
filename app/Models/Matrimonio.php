<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matrimonio extends Model
{
    use HasFactory;

    protected $table = 'matrimonios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cve_persona',
        'cve_persona1',
        'Per_cve_padre',
        'Per_cve_madre',
        'Per_cve_padre1',
        'Per_cve_madre1',
        'libro',
        'foja',
        'cve_ermitas',
        'Per_cve_padrino',
        'Per_cve_madrina',
        'fecha',
        'cve_sacramentos',
        'cve_sacerdotes',
        'cve_obispos',
    ];

    public $timestamps = false;

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'cve_persona');
    }

    public function persona1()
    {
        return $this->belongsTo(Persona::class, 'cve_persona1');
    }

    public function padre()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_padre');
    }

    public function madre()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_madre');
    }

    public function padre1()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_padre1');
    }

    public function madre1()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_madre1');
    }

    public function ermita()
    {
        return $this->belongsTo(Ermita::class, 'cve_ermitas');
    }

    public function padrino()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_padrino');
    }

    public function madrina()
    {
        return $this->belongsTo(Persona::class, 'Per_cve_madrina');
    }

    public function sacerdote()
    {
        return $this->belongsTo(Sacerdote::class, 'cve_sacerdotes');
    }

    public function obispo()
    {
        return $this->belongsTo(Obispo::class, 'cve_obispos');
    }

    public function sacramento()
    {
        return $this->belongsTo(Sacramento::class, 'cve_sacramentos');
    }
}
