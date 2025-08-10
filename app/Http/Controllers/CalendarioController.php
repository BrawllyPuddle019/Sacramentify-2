<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evento;
use App\Models\Sacerdote;
use App\Models\Ermita;
use App\Models\Persona;
use App\Models\Platica;
use App\Models\Bautizo;
use App\Models\Confirmacion;
use App\Models\Matrimonio;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CalendarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar la vista principal del calendario
     */
    public function index()
    {
        $sacerdotes = Sacerdote::all();
        $ermitas = Ermita::all();
        $personas = Persona::all();
        
        return view('calendario.index', compact('sacerdotes', 'ermitas', 'personas'));
    }

    /**
     * Obtener eventos en formato JSON para FullCalendar
     */
    public function obtenerEventos(Request $request)
    {
        $inicio = $request->start;
        $fin = $request->end;

        $eventos = Evento::with(['sacerdote', 'ermita'])
            ->entreFechas($inicio, $fin);

        // Filtrar por tipo si se especifica
        if ($request->has('tipo') && $request->tipo !== 'todos') {
            $eventos->where('tipo', $request->tipo);
        }

        // Filtrar por estado si se especifica
        if ($request->has('estado') && $request->estado !== 'todos') {
            $eventos->where('estado', $request->estado);
        }

        // Solo mostrar eventos propios a usuarios no admin
        if (!auth()->user()->is_admin) {
            $eventos->where('user_id', auth()->id());
        }

        $eventos = $eventos->get();

        return response()->json(
            $eventos->map(function ($evento) {
                return $evento->formatoFullCalendar();
            })
        );
    }

    /**
     * Crear un nuevo evento
     */
    public function store(Request $request)
    {
        try {
            Log::info('Creando evento con datos:', $request->all());
            
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'tipo' => 'required|in:platica,bautizo,confirmacion,matrimonio,otro',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',
                'sacerdote_id' => 'nullable|exists:sacerdotes,cve_sacerdotes',
                'ermita_id' => 'nullable|exists:ermitas,cve_ermitas',
                'contacto_email' => 'nullable|email',
                'contacto_telefono' => 'nullable|string|max:20',
            ]);

            if ($validator->fails()) {
                Log::error('Errores de validación:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar conflictos de horario si hay sacerdote asignado
            if ($request->sacerdote_id) {
                $conflicto = Evento::where('sacerdote_id', $request->sacerdote_id)
                    ->where('estado', '!=', 'cancelado')
                    ->where(function ($query) use ($request) {
                        $query->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                              ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin])
                              ->orWhere(function ($q) use ($request) {
                                  $q->where('fecha_inicio', '<=', $request->fecha_inicio)
                                    ->where('fecha_fin', '>=', $request->fecha_fin);
                              });
                    })->exists();

                if ($conflicto) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El sacerdote ya tiene un compromiso en ese horario.'
                    ], 422);
                }
            }

            $coloresPorTipo = [
                'platica' => '#27ae60',
                'bautizo' => '#3498db',
                'confirmacion' => '#f39c12',
                'matrimonio' => '#e74c3c',
                'otro' => '#95a5a6'
            ];

            $evento = Evento::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'tipo' => $request->tipo,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'todo_el_dia' => $request->has('todo_el_dia'),
                'estado' => auth()->user()->is_admin ? 'confirmado' : 'pendiente',
                'color' => $coloresPorTipo[$request->tipo],
                'user_id' => auth()->id(),
                'sacerdote_id' => $request->sacerdote_id,
                'ermita_id' => $request->ermita_id,
                'padre_id' => $request->padre_id,
                'madre_id' => $request->madre_id,
                'persona_principal_id' => $request->persona_principal_id,
                'padrino_id' => $request->padrino_id,
                'madrina_id' => $request->madrina_id,
                'contacto_email' => $request->contacto_email,
                'contacto_telefono' => $request->contacto_telefono,
                'notas' => $request->notas,
            ]);

            Log::info('Evento creado exitosamente:', $evento->toArray());

            return response()->json([
                'success' => true,
                'evento' => $evento->formatoFullCalendar(),
                'message' => 'Evento creado exitosamente.'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al crear evento:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar detalles de un evento
     */
    public function show($id)
    {
        $evento = Evento::with(['sacerdote', 'ermita', 'user'])->findOrFail($id);

        // Verificar permisos
        if (!auth()->user()->is_admin && $evento->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para ver este evento.'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'evento' => $evento->formatoFullCalendar()
        ]);
    }

    /**
     * Actualizar un evento
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        // Verificar permisos
        if (!auth()->user()->is_admin && $evento->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para modificar este evento.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:platica,bautizo,confirmacion,matrimonio,otro',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'sacerdote_id' => 'nullable|exists:sacerdotes,id',
            'ermita_id' => 'nullable|exists:ermitas,id',
            'contacto_email' => 'nullable|email',
            'contacto_telefono' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Solo admin puede cambiar estado
        $estado = $evento->estado;
        if (auth()->user()->is_admin && $request->has('estado')) {
            $estado = $request->estado;
        }

        $evento->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'tipo' => $request->tipo,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,
            'todo_el_dia' => $request->has('todo_el_dia'),
            'estado' => $estado,
            'sacerdote_id' => $request->sacerdote_id,
            'ermita_id' => $request->ermita_id,
            'contacto_email' => $request->contacto_email,
            'contacto_telefono' => $request->contacto_telefono,
            'notas' => $request->notas,
        ]);

        return response()->json([
            'success' => true,
            'evento' => $evento->formatoFullCalendar(),
            'message' => 'Evento actualizado exitosamente.'
        ]);
    }

    /**
     * Eliminar un evento
     */
    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);

        // Verificar permisos
        if (!auth()->user()->is_admin && $evento->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para eliminar este evento.'
            ], 403);
        }

        $evento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evento eliminado exitosamente.'
        ]);
    }

    /**
     * Cambiar estado de un evento (solo admin)
     */
    public function cambiarEstado(Request $request, $id)
    {
        Log::info('🔄 Inicio de cambiarEstado', [
            'evento_id' => $id,
            'nuevo_estado' => $request->estado,
            'user_id' => auth()->id(),
            'is_admin' => auth()->user()->is_admin
        ]);
        
        if (!auth()->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para cambiar el estado del evento.'
            ], 403);
        }

        $evento = Evento::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:pendiente,confirmado,cancelado,completado'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $estadoAnterior = $evento->estado;
        $evento->update(['estado' => $request->estado]);

        Log::info('✅ Estado actualizado', [
            'evento_id' => $evento->id,
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $request->estado
        ]);

        // Si se marca como completado, crear registro en la tabla correspondiente
        if ($request->estado === 'completado' && $estadoAnterior !== 'completado') {
            Log::info('Iniciando proceso de completado para evento:', [
                'evento_id' => $evento->id,
                'tipo' => $evento->tipo,
                'padre_id' => $evento->padre_id,
                'madre_id' => $evento->madre_id
            ]);
            
            try {
                $this->crearRegistroCompletado($evento);
            } catch (\Exception $e) {
                Log::error('Error al crear registro completado:', [
                    'evento_id' => $evento->id,
                    'tipo' => $evento->tipo,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // No fallar la actualización de estado, solo log del error
            }
        }

        return response()->json([
            'success' => true,
            'evento' => $evento->formatoFullCalendar(),
            'message' => 'Estado del evento actualizado exitosamente.'
        ]);
    }

    /**
     * Crear registro en la tabla correspondiente cuando se completa un evento
     */
    private function crearRegistroCompletado(Evento $evento)
    {
        Log::info('Entrando a crearRegistroCompletado:', [
            'evento_id' => $evento->id,
            'tipo' => $evento->tipo
        ]);
        
        switch ($evento->tipo) {
            case 'platica':
                Log::info('Procesando plática:', [
                    'padre_id' => $evento->padre_id,
                    'madre_id' => $evento->madre_id
                ]);
                
                if ($evento->padre_id && $evento->madre_id) {
                    $this->crearPlatica($evento, 'Plática');
                } else {
                    Log::warning('No se puede crear plática - faltan padre o madre:', [
                        'evento_id' => $evento->id,
                        'padre_id' => $evento->padre_id,
                        'madre_id' => $evento->madre_id
                    ]);
                }
                break;
                
            case 'bautizo':
                Log::info('Procesando bautizo:', [
                    'persona_principal_id' => $evento->persona_principal_id,
                    'padrino_id' => $evento->padrino_id,
                    'madrina_id' => $evento->madrina_id
                ]);
                
                if ($evento->persona_principal_id && $evento->padrino_id && $evento->madrina_id) {
                    // Para bautizos usamos padrino como padre y madrina como madre en la tabla platicas
                    $evento->padre_id = $evento->padrino_id;
                    $evento->madre_id = $evento->madrina_id;
                    $this->crearPlatica($evento, 'Bautizo');
                } else {
                    Log::warning('No se puede crear registro de bautizo - faltan datos:', [
                        'evento_id' => $evento->id,
                        'persona_principal_id' => $evento->persona_principal_id,
                        'padrino_id' => $evento->padrino_id,
                        'madrina_id' => $evento->madrina_id
                    ]);
                }
                break;
                
            case 'confirmacion':
                Log::info('Procesando confirmación:', [
                    'persona_principal_id' => $evento->persona_principal_id,
                    'padrino_id' => $evento->padrino_id,
                    'madrina_id' => $evento->madrina_id
                ]);
                
                if ($evento->persona_principal_id && $evento->padrino_id && $evento->madrina_id) {
                    // Para confirmaciones usamos padrino como padre y madrina como madre en la tabla platicas
                    $evento->padre_id = $evento->padrino_id;
                    $evento->madre_id = $evento->madrina_id;
                    $this->crearPlatica($evento, 'Confirmación');
                } else {
                    Log::warning('No se puede crear registro de confirmación - faltan datos:', [
                        'evento_id' => $evento->id,
                        'persona_principal_id' => $evento->persona_principal_id,
                        'padrino_id' => $evento->padrino_id,
                        'madrina_id' => $evento->madrina_id
                    ]);
                }
                break;
                
            case 'matrimonio':
                Log::info('Procesando matrimonio:', [
                    'padre_id' => $evento->padre_id, // novio
                    'madre_id' => $evento->madre_id  // novia
                ]);
                
                if ($evento->padre_id && $evento->madre_id) {
                    // Para matrimonios usamos novio como padre y novia como madre
                    $this->crearPlatica($evento, 'Matrimonio');
                } else {
                    Log::warning('No se puede crear registro de matrimonio - faltan novio o novia:', [
                        'evento_id' => $evento->id,
                        'padre_id' => $evento->padre_id,
                        'madre_id' => $evento->madre_id
                    ]);
                }
                break;
        }
    }

    /**
     * Crear registro en la tabla platicas
     */
    private function crearPlatica(Evento $evento, $tipoSacramento)
    {
        $platicaData = [
            'padre' => $evento->padre_id,
            'madre' => $evento->madre_id,
            'nombre' => $tipoSacramento . ': ' . $evento->titulo,
            'fecha' => $evento->fecha_inicio->format('Y-m-d')
        ];
        
        Log::info('Creando registro en platicas con datos:', $platicaData);
        
        $platica = Platica::create($platicaData);
        
        Log::info('Registro creado exitosamente:', [
            'platica_id' => $platica->cve_platicas,
            'tipo' => $tipoSacramento
        ]);
        
        // Actualizar evento con referencia a la plática creada
        $evento->update(['platica_id' => $platica->cve_platicas]);
        
        Log::info('Evento actualizado con platica_id:', [
            'evento_id' => $evento->id,
            'platica_id' => $platica->cve_platicas
        ]);
    }
}
