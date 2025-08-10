<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descripcion',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'todo_el_dia',
        'estado',
        'color',
        'user_id',
        'sacerdote_id',
        'ermita_id',
        'padre_id',
        'madre_id',
        'persona_principal_id',
        'padrino_id',
        'madrina_id',
        'bautizo_id',
        'confirmacion_id',
        'matrimonio_id',
        'platica_id',
        'personas_involucradas',
        'notas',
        'contacto_email',
        'contacto_telefono'
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'todo_el_dia' => 'boolean',
        'personas_involucradas' => 'array',
    ];

    // Relaciones
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sacerdote()
    {
        return $this->belongsTo(Sacerdote::class, 'sacerdote_id', 'cve_sacerdotes');
    }

    public function ermita()
    {
        return $this->belongsTo(Ermita::class, 'ermita_id', 'cve_ermitas');
    }

    public function bautizo()
    {
        return $this->belongsTo(Bautizo::class, 'bautizo_id', 'cve_bautizos');
    }

    public function confirmacion()
    {
        return $this->belongsTo(Confirmacion::class, 'confirmacion_id', 'cve_confirmaciones');
    }

    public function matrimonio()
    {
        return $this->belongsTo(Matrimonio::class, 'matrimonio_id', 'cve_matrimonios');
    }

    public function platica()
    {
        return $this->belongsTo(Platica::class, 'platica_id', 'cve_platicas');
    }

    // Relaciones con personas
    public function padre()
    {
        return $this->belongsTo(Persona::class, 'padre_id', 'cve_persona');
    }

    public function madre()
    {
        return $this->belongsTo(Persona::class, 'madre_id', 'cve_persona');
    }

    public function personaPrincipal()
    {
        return $this->belongsTo(Persona::class, 'persona_principal_id', 'cve_persona');
    }

    public function padrino()
    {
        return $this->belongsTo(Persona::class, 'padrino_id', 'cve_persona');
    }

    public function madrina()
    {
        return $this->belongsTo(Persona::class, 'madrina_id', 'cve_persona');
    }

    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeConfirmados($query)
    {
        return $query->where('estado', 'confirmado');
    }

    public function scopeEntreFechas($query, $inicio, $fin)
    {
        return $query->whereBetween('fecha_inicio', [$inicio, $fin])
                    ->orWhereBetween('fecha_fin', [$inicio, $fin]);
    }

    // Métodos auxiliares
    public function esProximoEvento()
    {
        return $this->fecha_inicio->isFuture() && 
               $this->fecha_inicio->diffInDays(now()) <= 7;
    }

    public function tieneConflicto($fecha_inicio, $fecha_fin, $sacerdote_id = null)
    {
        $query = self::where('id', '!=', $this->id)
                    ->where('estado', '!=', 'cancelado')
                    ->where(function ($q) use ($fecha_inicio, $fecha_fin) {
                        $q->whereBetween('fecha_inicio', [$fecha_inicio, $fecha_fin])
                          ->orWhereBetween('fecha_fin', [$fecha_inicio, $fecha_fin])
                          ->orWhere(function ($q2) use ($fecha_inicio, $fecha_fin) {
                              $q2->where('fecha_inicio', '<=', $fecha_inicio)
                                 ->where('fecha_fin', '>=', $fecha_fin);
                          });
                    });

        if ($sacerdote_id) {
            $query->where('sacerdote_id', $sacerdote_id);
        }

        return $query->exists();
    }

    public function formatoFullCalendar()
    {
        // Verificar que las fechas no sean nulas
        $fechaInicio = $this->fecha_inicio ? $this->fecha_inicio->toISOString() : null;
        $fechaFin = $this->fecha_fin ? $this->fecha_fin->toISOString() : null;

        return [
            'id' => $this->id,
            'title' => $this->titulo,
            'start' => $fechaInicio,
            'end' => $fechaFin,
            'allDay' => $this->todo_el_dia,
            'backgroundColor' => $this->color,
            'borderColor' => $this->color,
            'textColor' => '#ffffff',
            'extendedProps' => [
                'tipo' => $this->tipo,
                'estado' => $this->estado,
                'descripcion' => $this->descripcion,
                'sacerdote' => $this->sacerdote ? $this->sacerdote->nombre_sacerdote . ' ' . $this->sacerdote->apellido_paterno : null,
                'ermita' => $this->ermita ? $this->ermita->nombre_ermita : null,
                'contacto_email' => $this->contacto_email,
                'contacto_telefono' => $this->contacto_telefono,
                'notas' => $this->notas,
            ]
        ];
    }
}
