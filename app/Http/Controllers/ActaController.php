<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acta;
use App\Models\Persona;
use App\Models\Sacramento;
use App\Models\Obispo;
use App\Models\Sacerdote;
use App\Models\Ermita;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ActaController extends Controller
{
    // Función auxiliar para determinar si una persona es hombre
    private function esHombre($persona)
    {
        if (!$persona) return false;
        return $persona->sexo == 1 || $persona->sexo == '1' || strtolower($persona->sexo) == 'm';
    }

    public function index(Request $request)
    {
        $actas = Acta::query();

        // Filtro por nombre de persona
        if ($request->filled('nombre')) {
            $actas->whereHas('persona', function ($query) use ($request) {
                $query->where('nombre', 'like', '%' . $request->nombre . '%')
                    ->orWhere('apellido_paterno', 'like', '%' . $request->nombre . '%')
                    ->orWhere('apellido_materno', 'like', '%' . $request->nombre . '%');
            })->orWhereHas('persona2', function ($query) use ($request) {
                $query->where('nombre', 'like', '%' . $request->nombre . '%')
                    ->orWhere('apellido_paterno', 'like', '%' . $request->nombre . '%')
                    ->orWhere('apellido_materno', 'like', '%' . $request->nombre . '%');
            });
        }

        // Filtro por tipo de acta
        if ($request->filled('tipo')) {
            $actas->whereHas('tipoActa', function ($query) use ($request) {
                $query->where('nombre', $request->tipo);
            });
        }

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $actas->where('fecha', $request->fecha);
        }

        // Filtros adicionales existentes
        if ($request->filled('persona')) {
            $actas->whereHas('persona', function ($query) use ($request) {
                $query->where('nombre', 'like', '%' . $request->persona . '%')
                    ->orWhere('apellido_paterno', 'like', '%' . $request->persona . '%')
                    ->orWhere('apellido_materno', 'like', '%' . $request->persona . '%');
            });
        }

        if ($request->filled('sacramento')) {
            $actas->where('tipo_acta', $request->sacramento);
        }

        if ($request->filled('ermita')) {
            $actas->where('cve_ermitas', $request->ermita);
        }

        if ($request->filled('sacerdote')) {
            $actas->where('cve_sacerdotes_celebrante', $request->sacerdote);
        }

        $actas = $actas->with([
            'persona', 'persona2',
            'padrino1', 'madrina1',
            'ermita',
            'sacerdoteCelebrante', 'sacerdoteAsistente',
            'obispoCelebrante',
            'padrino', 'madrina', 'padre', 'madre', 'padre1', 'madre1',
            'tipoActa'
        ])->paginate(15);

        $personas = Persona::all();
        $sacramentos = Sacramento::all();
        $sacerdotes = Sacerdote::all();
        $obispos = Obispo::all();
        $ermitas = Ermita::all();
        
        return view('actas.index', compact('actas', 'personas', 'sacramentos', 'sacerdotes', 'obispos', 'ermitas'));
    }

    public function create()
    {
        $personas = Persona::all();
        $sacramentos = Sacramento::all();
        $sacerdotes = Sacerdote::all();
        $obispos = Obispo::all();
        $ermitas = Ermita::all();
        return view('actas.create', compact('personas', 'sacramentos', 'sacerdotes', 'obispos', 'ermitas'));
    }

    public function show($id)
    {
        $acta = Acta::with(['persona', 'persona2', 'tipoActa', 'sacerdoteCelebrante', 'sacerdoteAsistente', 'obispoCelebrante', 'ermita'])->findOrFail($id);
        
        return view('actas.show', compact('acta'));
    }

    public function store(Request $request)
    {
        // 🔍 DEBUG: Ver qué datos llegan
        Log::info('=== DATOS RECIBIDOS EN STORE ===');
        Log::info('Todos los datos', $request->all());
        Log::info('Tipo de acta', ['tipo_acta' => $request->tipo_acta]);
        if ($request->has('bautizo')) {
            Log::info('Datos de bautizo', $request->bautizo ?? []);
        }
        if ($request->has('confirmacion')) {
            Log::info('Datos de confirmación', $request->confirmacion ?? []);
        }
        
        // Validar campos básicos
        $request->validate([
            'tipo_acta' => 'required',
            'fecha' => 'required|date',
            'Libro' => 'required',
            'Fojas' => 'required|numeric',
            'Folio' => 'required|numeric',
        ]);

        // Identifica el tipo de sacramento
        $sacramento = Sacramento::find($request->tipo_acta);
        $tipoOriginal = strtolower($sacramento ? $sacramento->nombre : '');
        
        // Mapear tipos para consistencia con el frontend
        $tipo = match($tipoOriginal) {
            'matrimonio' => 'matrimonio',
            'bautismo' => 'bautizo',      // ← MAPEO CRUCIAL
            'confirmación' => 'confirmacion',  // ← MAPEO CRUCIAL
            default => $tipoOriginal
        };
        
        // 🔍 DEBUG: Verificar identificación del tipo
        Log::info('Sacramento encontrado', [
            'id' => $request->tipo_acta, 
            'nombre_original' => $tipoOriginal, 
            'tipo_mapeado' => $tipo
        ]);

        // Validaciones específicas por tipo de sacramento para evitar duplicados
        if ($tipo == 'matrimonio' && $request->has('matrimonio')) {
            $esposo = $request->matrimonio['cve_esposo'] ?? null;
            $esposa = $request->matrimonio['cve_esposa'] ?? null;
            
            if (!$esposo || !$esposa) {
                return redirect()->back()->withErrors(['error' => 'Debe seleccionar tanto al esposo como a la esposa.'])->withInput();
            }

            // Verificar si alguno ya está casado
            $matrimonioExistente = Acta::where('tipo_acta', $request->tipo_acta)
                ->where(function($query) use ($esposo, $esposa) {
                    $query->where('cve_persona', $esposo)
                          ->orWhere('cve_persona2', $esposo)
                          ->orWhere('cve_persona', $esposa)
                          ->orWhere('cve_persona2', $esposa);
                })
                ->first();

            if ($matrimonioExistente) {
                $personaCasada = $matrimonioExistente->cve_persona == $esposo || $matrimonioExistente->cve_persona2 == $esposo 
                    ? Persona::find($esposo)->nombre . ' ' . Persona::find($esposo)->apellido_paterno
                    : Persona::find($esposa)->nombre . ' ' . Persona::find($esposa)->apellido_paterno;
                return redirect()->back()->withErrors(['error' => "La persona {$personaCasada} ya tiene un acta de matrimonio registrada."])->withInput();
            }
        }

        if ($tipo == 'bautizo' && $request->has('bautizo')) {
            $persona = $request->bautizo['cve_persona'] ?? null;
            
            if (!$persona) {
                return redirect()->back()->withErrors(['error' => 'Debe seleccionar la persona que se bautiza.'])->withInput();
            }

            // Verificar si ya está bautizada
            $bautizoExistente = Acta::where('tipo_acta', $request->tipo_acta)
                ->where('cve_persona', $persona)
                ->first();

            if ($bautizoExistente) {
                $nombrePersona = Persona::find($persona)->nombre . ' ' . Persona::find($persona)->apellido_paterno;
                return redirect()->back()->withErrors(['error' => "La persona {$nombrePersona} ya tiene un acta de bautizo registrada."])->withInput();
            }
        }

        if ($tipo == 'confirmacion' && $request->has('confirmacion')) {
            $persona = $request->confirmacion['cve_persona'] ?? null;
            
            if (!$persona) {
                return redirect()->back()->withErrors(['error' => 'Debe seleccionar la persona que se confirma.'])->withInput();
            }

            // Verificar si ya está confirmada
            $confirmacionExistente = Acta::where('tipo_acta', $request->tipo_acta)
                ->where('cve_persona', $persona)
                ->first();

            if ($confirmacionExistente) {
                $nombrePersona = Persona::find($persona)->nombre . ' ' . Persona::find($persona)->apellido_paterno;
                return redirect()->back()->withErrors(['error' => "La persona {$nombrePersona} ya tiene un acta de confirmación registrada."])->withInput();
            }
        }

        $acta = new Acta();

        // Campos generales
        $acta->tipo_acta = $request->tipo_acta;
        $acta->cve_ermitas = $request->cve_ermitas;
        $acta->cve_sacerdotes_celebrante = $request->cve_sacerdotes_celebrante;
        $acta->cve_sacerdotes_asistente = $request->cve_sacerdotes_asistente;
        $acta->cve_obispos_celebrante = $request->cve_obispos_celebrante;
        $acta->fecha = $request->fecha;
        $acta->Libro = $request->Libro;
        $acta->Fojas = $request->Fojas;
        $acta->Folio = $request->Folio;

    // MATRIMONIO
    if ($tipo == 'matrimonio' && $request->has('matrimonio')) {
    $acta->cve_persona = $request->matrimonio['cve_esposo'] ?? null;
    $acta->cve_persona2 = $request->matrimonio['cve_esposa'] ?? null;

    // Esposo
    $acta->Per_cve_padre = $request->matrimonio['padre_esposo'] ?? null;
    $acta->Per_cve_madre = $request->matrimonio['madre_esposo'] ?? null;
    $acta->Per_cve_padrino1 = $request->matrimonio['padrino_esposo'] ?? null;
    $acta->Per_cve_madrina1 = $request->matrimonio['madrina_esposo'] ?? null;

    // Esposa
    $acta->Per_cve_padre1 = $request->matrimonio['padre_esposa'] ?? null;
    $acta->Per_cve_madre1 = $request->matrimonio['madre_esposa'] ?? null;
    $acta->Per_cve_padrino = $request->matrimonio['padrino_esposa'] ?? null;
    $acta->Per_cve_madrina = $request->matrimonio['madrina_esposa'] ?? null;
    }
    

    // BAUTIZO
    if ($tipo == 'bautizo' && $request->has('bautizo')) {
        Log::info('=== PROCESANDO BAUTIZO ===');
        Log::info('Datos de bautizo recibidos', $request->bautizo);
        
        $acta->cve_persona = $request->bautizo['cve_persona'] ?? null;
        Log::info('Persona principal asignada', ['cve_persona' => $acta->cve_persona]);
        
        // Obtener el sexo de la persona que se bautiza
        $persona = Persona::find($request->bautizo['cve_persona']);
        
        if ($persona) {
            Log::info('Persona encontrada', [
                'id' => $persona->cve_persona,
                'nombre' => $persona->nombre,
                'sexo' => $persona->sexo,
                'es_hombre' => $this->esHombre($persona)
            ]);
            
            if ($this->esHombre($persona)) { // Hombre
                // Padres/madres van en campos de esposo
                $acta->Per_cve_padre = $request->bautizo['padre'] ?? null;
                $acta->Per_cve_madre = $request->bautizo['madre'] ?? null;
                $acta->Per_cve_padrino1 = $request->bautizo['padrino'] ?? null;
                $acta->Per_cve_madrina1 = $request->bautizo['madrina'] ?? null;
                
                Log::info('Asignando a campos de HOMBRE', [
                    'Per_cve_padre' => $acta->Per_cve_padre,
                    'Per_cve_madre' => $acta->Per_cve_madre,
                    'Per_cve_padrino1' => $acta->Per_cve_padrino1,
                    'Per_cve_madrina1' => $acta->Per_cve_madrina1
                ]);
            } else { // Mujer
                // Padres/madres van en campos de esposa
                $acta->Per_cve_padre1 = $request->bautizo['padre'] ?? null;
                $acta->Per_cve_madre1 = $request->bautizo['madre'] ?? null;
                $acta->Per_cve_padrino = $request->bautizo['padrino'] ?? null;
                $acta->Per_cve_madrina = $request->bautizo['madrina'] ?? null;
                
                Log::info('Asignando a campos de MUJER', [
                    'Per_cve_padre1' => $acta->Per_cve_padre1,
                    'Per_cve_madre1' => $acta->Per_cve_madre1,
                    'Per_cve_padrino' => $acta->Per_cve_padrino,
                    'Per_cve_madrina' => $acta->Per_cve_madrina
                ]);
            }
        } else {
            Log::error('No se encontró la persona para bautizo', ['cve_persona' => $request->bautizo['cve_persona']]);
        }
    }

    // CONFIRMACION
    if ($tipo == 'confirmacion' && $request->has('confirmacion')) {
        $acta->cve_persona = $request->confirmacion['cve_persona'] ?? null;
        
        // Obtener el sexo de la persona que se confirma
        $persona = Persona::find($request->confirmacion['cve_persona']);
        
        if ($persona) {
            if ($this->esHombre($persona)) { // Hombre
                // Padres/madres van en campos de esposo
                $acta->Per_cve_padre = $request->confirmacion['padre'] ?? null;
                $acta->Per_cve_madre = $request->confirmacion['madre'] ?? null;
                // Padrinos de confirmación van en campos de esposo
                $acta->Per_cve_padrino1 = $request->confirmacion['padrino'] ?? null;
                $acta->Per_cve_madrina1 = $request->confirmacion['madrina'] ?? null;
            } else { // Mujer
                // Padres/madres van en campos de esposa
                $acta->Per_cve_padre1 = $request->confirmacion['padre'] ?? null;
                $acta->Per_cve_madre1 = $request->confirmacion['madre'] ?? null;
                // Padrinos de confirmación van en campos de esposa
                $acta->Per_cve_padrino = $request->confirmacion['padrino'] ?? null;
                $acta->Per_cve_madrina = $request->confirmacion['madrina'] ?? null;
            }
        }
    }

    $acta->save();
    
    // Consumir un crédito después de crear el acta exitosamente
    if (auth()->check()) {
        /** @var User $user */
        $user = auth()->user();
        if (!$user->consumeCredits(1)) {
            // Si no se pueden consumir créditos (por alguna razón), 
            // eliminar el acta y mostrar error
            $acta->delete();
            return redirect()->route('payments.index')
                ->with('error', 'No tienes créditos suficientes para crear esta acta.');
        }
    }
    
    // Asignar número consecutivo después de guardar
    $this->asignarNumeroConsecutivo($acta);

    return redirect()->route('actas.index')->with('success', 'Acta creada correctamente. Se ha descontado 1 crédito de tu cuenta.');
    }

    // Método para validar si una persona ya tiene un sacramento registrado
    public function validarSacramento(Request $request)
    {
        $tipoActa = $request->tipo_acta;
        $personaId = $request->persona_id;
        $personaId2 = $request->persona_id2; // Para matrimonio
        $actaId = $request->acta_id; // Para excluir en edición

        if (!$tipoActa || !$personaId) {
            return response()->json(['valido' => true]);
        }

        $sacramento = Sacramento::find($tipoActa);
        $tipo = strtolower($sacramento ? $sacramento->nombre : '');

        $existe = false;
        $mensaje = '';

        if ($tipo == 'matrimonio') {
            // Para matrimonio verificar ambas personas
            $personas = [$personaId];
            if ($personaId2) {
                $personas[] = $personaId2;
            }

            $query = Acta::where('tipo_acta', $tipoActa)
                ->where(function($query) use ($personas) {
                    foreach ($personas as $persona) {
                        $query->orWhere('cve_persona', $persona)
                              ->orWhere('cve_persona2', $persona);
                    }
                });
            
            // Excluir acta actual si es edición
            if ($actaId) {
                $query->where('cve_actas', '!=', $actaId);
            }
            
            $matrimonioExistente = $query->first();

            if ($matrimonioExistente) {
                $existe = true;
                $personaCasada = null;
                if ($matrimonioExistente->cve_persona == $personaId || $matrimonioExistente->cve_persona2 == $personaId) {
                    $personaCasada = Persona::find($personaId);
                } elseif ($personaId2 && ($matrimonioExistente->cve_persona == $personaId2 || $matrimonioExistente->cve_persona2 == $personaId2)) {
                    $personaCasada = Persona::find($personaId2);
                }
                
                if ($personaCasada) {
                    $mensaje = "La persona {$personaCasada->nombre} {$personaCasada->apellido_paterno} ya tiene un acta de matrimonio registrada.";
                }
            }
        } else {
            // Para bautizo y confirmación
            $query = Acta::where('tipo_acta', $tipoActa)
                ->where('cve_persona', $personaId);
            
            // Excluir acta actual si es edición
            if ($actaId) {
                $query->where('cve_actas', '!=', $actaId);
            }
            
            $actaExistente = $query->first();

            if ($actaExistente) {
                $existe = true;
                $persona = Persona::find($personaId);
                $tipoNombre = $tipo == 'bautizo' ? 'bautizo' : 'confirmacion';
                $mensaje = "La persona {$persona->nombre} {$persona->apellido_paterno} ya tiene un acta de {$tipoNombre} registrada.";
            }
        }

        return response()->json([
            'valido' => !$existe,
            'mensaje' => $mensaje
        ]);
    }

    public function edit($id)
    {
        $acta = Acta::findOrFail($id);
        $personas = Persona::all();
        $sacramentos = Sacramento::all();
        $sacerdotes = Sacerdote::all();
        $obispos = Obispo::all();
        $ermitas = Ermita::all();

        return view('actas.edit', compact('acta', 'personas', 'sacramentos', 'sacerdotes', 'obispos', 'ermitas'));
    }

    public function update(Request $request, $id)
    {
        Log::info('=== INICIO UPDATE ACTA ===', ['acta_id' => $id, 'request_data' => $request->all()]);
        
        // Validar campos básicos
        $request->validate([
            'tipo_acta' => 'required',
            'fecha' => 'required|date',
            'Libro' => 'required',
            'Fojas' => 'required|numeric',
            'Folio' => 'required|numeric',
        ]);

        $acta = Acta::findOrFail($id);
        
        // Identifica el tipo de sacramento
        $sacramento = Sacramento::find($request->tipo_acta);
        $tipoOriginal = strtolower($sacramento ? $sacramento->nombre : '');

        // Mapear tipos para consistencia con el frontend
        $tipo = match($tipoOriginal) {
            'bautismo' => 'bautizo',
            'confirmación' => 'confirmacion',
            default => $tipoOriginal
        };

        // 🔍 DEBUG: Verificar identificación del tipo
        Log::info('Identificación del tipo en UPDATE', [
            'id' => $request->tipo_acta, 
            'nombre_original' => $tipoOriginal, 
            'tipo_mapeado' => $tipo
        ]);

        // Validaciones específicas por tipo de sacramento para evitar duplicados (excluyendo el registro actual)
        if ($tipo == 'matrimonio' && $request->has('matrimonio')) {
            $esposo = $request->matrimonio['cve_esposo'] ?? null;
            $esposa = $request->matrimonio['cve_esposa'] ?? null;
            
            if (!$esposo || !$esposa) {
                return redirect()->back()->withErrors(['error' => 'Debe seleccionar tanto al esposo como a la esposa.'])->withInput();
            }

            // Verificar si alguno ya está casado (excluyendo el acta actual)
            $matrimonioExistente = Acta::where('tipo_acta', $request->tipo_acta)
                ->where('cve_actas', '!=', $id) // Excluir el acta actual
                ->where(function($query) use ($esposo, $esposa) {
                    $query->where('cve_persona', $esposo)
                          ->orWhere('cve_persona2', $esposo)
                          ->orWhere('cve_persona', $esposa)
                          ->orWhere('cve_persona2', $esposa);
                })
                ->first();

            if ($matrimonioExistente) {
                $personaCasada = $matrimonioExistente->cve_persona == $esposo || $matrimonioExistente->cve_persona2 == $esposo 
                    ? Persona::find($esposo)->nombre . ' ' . Persona::find($esposo)->apellido_paterno
                    : Persona::find($esposa)->nombre . ' ' . Persona::find($esposa)->apellido_paterno;
                return redirect()->back()->withErrors(['error' => "La persona {$personaCasada} ya tiene un acta de matrimonio registrada."])->withInput();
            }
        }

        if ($tipo == 'bautizo' && $request->has('bautizo')) {
            $persona = $request->bautizo['cve_persona'] ?? null;
            
            if (!$persona) {
                return redirect()->back()->withErrors(['error' => 'Debe seleccionar la persona que se bautiza.'])->withInput();
            }

            // Verificar si ya está bautizada (excluyendo el acta actual)
            $bautizoExistente = Acta::where('tipo_acta', $request->tipo_acta)
                ->where('cve_actas', '!=', $id) // Excluir el acta actual
                ->where('cve_persona', $persona)
                ->first();

            if ($bautizoExistente) {
                $nombrePersona = Persona::find($persona)->nombre . ' ' . Persona::find($persona)->apellido_paterno;
                return redirect()->back()->withErrors(['error' => "La persona {$nombrePersona} ya tiene un acta de bautizo registrada."])->withInput();
            }
        }

        if ($tipo == 'confirmacion' && $request->has('confirmacion')) {
            $persona = $request->confirmacion['cve_persona'] ?? null;
            
            if (!$persona) {
                return redirect()->back()->withErrors(['error' => 'Debe seleccionar la persona que se confirma.'])->withInput();
            }

            // Verificar si ya está confirmada (excluyendo el acta actual)
            $confirmacionExistente = Acta::where('tipo_acta', $request->tipo_acta)
                ->where('cve_actas', '!=', $id) // Excluir el acta actual
                ->where('cve_persona', $persona)
                ->first();

            if ($confirmacionExistente) {
                $nombrePersona = Persona::find($persona)->nombre . ' ' . Persona::find($persona)->apellido_paterno;
                return redirect()->back()->withErrors(['error' => "La persona {$nombrePersona} ya tiene un acta de confirmacion registrada."])->withInput();
            }
        }

        // Campos generales
        $acta->tipo_acta = $request->tipo_acta;
        $acta->cve_ermitas = $request->cve_ermitas;
        $acta->cve_sacerdotes_celebrante = $request->cve_sacerdotes_celebrante;
        $acta->cve_sacerdotes_asistente = $request->cve_sacerdotes_asistente;
        $acta->cve_obispos_celebrante = $request->cve_obispos_celebrante;
        $acta->fecha = $request->fecha;
        $acta->Libro = $request->Libro;
        $acta->Fojas = $request->Fojas;
        $acta->Folio = $request->Folio;

    // MATRIMONIO
    if ($tipo == 'matrimonio' && $request->has('matrimonio')) {
        $acta->cve_persona = $request->matrimonio['cve_esposo'] ?? null;
        $acta->cve_persona2 = $request->matrimonio['cve_esposa'] ?? null;
        
        // Esposo
        $acta->Per_cve_padre = $request->matrimonio['padre_esposo'] ?? null;
        $acta->Per_cve_madre = $request->matrimonio['madre_esposo'] ?? null;
        $acta->Per_cve_padrino1 = $request->matrimonio['padrino_esposo'] ?? null;
        $acta->Per_cve_madrina1 = $request->matrimonio['madrina_esposo'] ?? null;

        // Esposa
        $acta->Per_cve_padre1 = $request->matrimonio['padre_esposa'] ?? null;
        $acta->Per_cve_madre1 = $request->matrimonio['madre_esposa'] ?? null;
        $acta->Per_cve_padrino = $request->matrimonio['padrino_esposa'] ?? null;
        $acta->Per_cve_madrina = $request->matrimonio['madrina_esposa'] ?? null;
    }

    // BAUTIZO
    if ($tipo == 'bautizo' && $request->has('bautizo')) {
        $acta->cve_persona = $request->bautizo['cve_persona'] ?? null;
        
        // Obtener el sexo de la persona que se bautiza
        $persona = Persona::find($request->bautizo['cve_persona']);
        
        if ($persona) {
            if ($this->esHombre($persona)) { // Hombre
                // Padres/madres van en campos de esposo
                $acta->Per_cve_padre = $request->bautizo['padre'] ?? null;
                $acta->Per_cve_madre = $request->bautizo['madre'] ?? null;
                $acta->Per_cve_padrino1 = $request->bautizo['padrino'] ?? null;
                $acta->Per_cve_madrina1 = $request->bautizo['madrina'] ?? null;
            } else { // Mujer
                // Padres/madres van en campos de esposa
                $acta->Per_cve_padre1 = $request->bautizo['padre'] ?? null;
                $acta->Per_cve_madre1 = $request->bautizo['madre'] ?? null;
                $acta->Per_cve_padrino = $request->bautizo['padrino'] ?? null;
                $acta->Per_cve_madrina = $request->bautizo['madrina'] ?? null;
            }
        }
    }

    // CONFIRMACION
    if ($tipo == 'confirmacion' && $request->has('confirmacion')) {
        $acta->cve_persona = $request->confirmacion['cve_persona'] ?? null;
        
        // Obtener el sexo de la persona que se confirma
        $persona = Persona::find($request->confirmacion['cve_persona']);
        
        if ($persona) {
            if ($this->esHombre($persona)) { // Hombre
                // Padres/madres van en campos de esposo
                $acta->Per_cve_padre = $request->confirmacion['padre'] ?? null;
                $acta->Per_cve_madre = $request->confirmacion['madre'] ?? null;
                // Padrinos de confirmación van en campos de esposo
                $acta->Per_cve_padrino1 = $request->confirmacion['padrino'] ?? null;
                $acta->Per_cve_madrina1 = $request->confirmacion['madrina'] ?? null;
            } else { // Mujer
                // Padres/madres van en campos de esposa
                $acta->Per_cve_padre1 = $request->confirmacion['padre'] ?? null;
                $acta->Per_cve_madre1 = $request->confirmacion['madre'] ?? null;
                // Padrinos de confirmación van en campos de esposa
                $acta->Per_cve_padrino = $request->confirmacion['padrino'] ?? null;
                $acta->Per_cve_madrina = $request->confirmacion['madrina'] ?? null;
            }
        }
    }

    $acta->save();

    Log::info('=== ACTA ACTUALIZADA EXITOSAMENTE ===', [
        'acta_id' => $acta->cve_actas,
        'tipo_sacramento' => $tipo,
        'persona_principal' => $acta->cve_persona,
        'persona_secundaria' => $acta->cve_persona2,
        'campos_actualizados' => [
            'Per_cve_padre' => $acta->Per_cve_padre,
            'Per_cve_madre' => $acta->Per_cve_madre,
            'Per_cve_padrino1' => $acta->Per_cve_padrino1,
            'Per_cve_madrina1' => $acta->Per_cve_madrina1,
            'Per_cve_padre1' => $acta->Per_cve_padre1,
            'Per_cve_madre1' => $acta->Per_cve_madre1,
            'Per_cve_padrino' => $acta->Per_cve_padrino,
            'Per_cve_madrina' => $acta->Per_cve_madrina,
        ]
    ]);

    return redirect()->route('actas.index')->with('success', 'Acta actualizada correctamente');
    }

    public function destroy($id)
    {
        $acta = Acta::findOrFail($id);
        $numeroEliminado = $acta->numero_consecutivo;
        
        // Soft delete
        $acta->delete();
        
        // Reordenar números consecutivos solo si tenía número asignado
        if ($numeroEliminado) {
            $this->reordenarNumerosConsecutivos($numeroEliminado);
        }
        
        return redirect()->route('actas.index')->with('success', 'Acta eliminada correctamente');
    }

    public function generarPDF($id)
    {
        // Cargar el acta con todas las relaciones necesarias
        $acta = Acta::with([
            'persona', 'persona.municipios', 'persona.municipios.estado',
            'persona2', 'persona2.municipios', 'persona2.municipios.estado',
            'padrino1', 'padrino1.municipios', 'padrino1.municipios.estado',
            'madrina1', 'madrina1.municipios', 'madrina1.municipios.estado',
            'padrino', 'padrino.municipios', 'padrino.municipios.estado',
            'madrina', 'madrina.municipios', 'madrina.municipios.estado',
            'padre', 'padre.municipios', 'padre.municipios.estado',
            'madre', 'madre.municipios', 'madre.municipios.estado',
            'padre1', 'padre1.municipios', 'padre1.municipios.estado',
            'madre1', 'madre1.municipios', 'madre1.municipios.estado',
            'ermita', 'sacerdoteCelebrante', 'obispoCelebrante',
            'tipoActa'
        ])->findOrFail($id);

        $data = ['acta' => $acta];
        $pdf = PDF::loadView('actas.pdf', $data);

        // Determinar el nombre del archivo basado en el tipo de sacramento
        $tipoSacramento = $acta->tipoActa ? strtolower($acta->tipoActa->nombre) : 'acta';
        $nombrePersona = '';
        
        if ($tipoSacramento == 'matrimonio') {
            $esposo = $acta->persona;
            $esposa = $acta->persona2;
            $nombrePersona = ($esposo ? $esposo->nombre . '_' . $esposo->apellido_paterno : '') . 
                           '_y_' . 
                           ($esposa ? $esposa->nombre . '_' . $esposa->apellido_paterno : '');
        } else {
            $persona = $acta->persona;
            $nombrePersona = $persona ? $persona->nombre . '_' . $persona->apellido_paterno : 'persona';
        }

        $nombreArchivo = 'acta_' . $tipoSacramento . '_' . $nombrePersona . '.pdf';
        $nombreArchivo = str_replace([' ', 'á', 'é', 'í', 'ó', 'ú', 'ñ'], ['_', 'a', 'e', 'i', 'o', 'u', 'n'], $nombreArchivo);

        return $pdf->download($nombreArchivo);
    }

    /**
     * Asigna número consecutivo a un acta
     */
    private function asignarNumeroConsecutivo(Acta $acta)
    {
        // Obtener el último número consecutivo
        $ultimoNumero = Acta::withoutTrashed()
            ->whereNotNull('numero_consecutivo')
            ->max('numero_consecutivo') ?? 0;
        
        // Asignar siguiente número
        $acta->numero_consecutivo = $ultimoNumero + 1;
        $acta->save();
    }

    /**
     * Reordena los números consecutivos después de una eliminación
     */
    private function reordenarNumerosConsecutivos($numeroEliminado)
    {
        // Decrementar todos los números mayores al eliminado
        Acta::withoutTrashed()
            ->where('numero_consecutivo', '>', $numeroEliminado)
            ->decrement('numero_consecutivo');
    }

    /**
     * Restaura un acta eliminada (soft delete)
     */
    public function restore($id)
    {
        $acta = Acta::withTrashed()->findOrFail($id);
        $acta->restore();
        
        // Reasignar número consecutivo
        $this->asignarNumeroConsecutivo($acta);
        
        return redirect()->route('actas.index')->with('success', 'Acta restaurada correctamente');
    }

    /**
     * Elimina permanentemente un acta
     */
    public function forceDelete($id)
    {
        $acta = Acta::withTrashed()->findOrFail($id);
        $numeroEliminado = $acta->numero_consecutivo;
        
        $acta->forceDelete();
        
        // Reordenar números si tenía número asignado
        if ($numeroEliminado) {
            $this->reordenarNumerosConsecutivos($numeroEliminado);
        }
        
        return redirect()->route('actas.index')->with('success', 'Acta eliminada permanentemente');
    }

    /**
     * Muestra las actas eliminadas (papelera)
     */
    public function trashed()
    {
        $actas = Acta::onlyTrashed()
            ->with([
                'persona', 'persona2',
                'padrino1', 'madrina1',
                'ermita',
                'sacerdoteCelebrante', 'sacerdoteAsistente',
                'obispoCelebrante',
                'padrino', 'madrina', 'padre', 'madre', 'padre1', 'madre1',
                'tipoActa'
            ])
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('actas.trashed', compact('actas'));
    }

    // ================================
    // MÉTODOS PARA API MÓVIL
    // ================================
    
    /**
     * Obtener actas de un usuario específico para móvil
     */
    public function getUserActas($userId)
    {
        try {
            $actas = Acta::with(['persona', 'sacramento', 'ermita'])
                ->whereHas('persona', function($query) use ($userId) {
                    // Aquí puedes ajustar la lógica según tu relación usuario-persona
                    // Por ahora, devuelvo todas las actas (puedes filtrar por algún campo)
                })
                ->orderBy('fecha', 'desc')
                ->take(20) // Limitar resultados para móvil
                ->get()
                ->map(function($acta) {
                    return [
                        'id' => $acta->cve_actas,
                        'tipo' => $acta->sacramento->nombre ?? 'Sin tipo',
                        'persona' => $acta->persona ? 
                            $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno . ' ' . $acta->persona->apellido_materno : 
                            'Sin persona',
                        'fecha' => $acta->fecha,
                        'ermita' => $acta->ermita->nombre ?? 'Sin ermita',
                        'libro' => $acta->Libro,
                        'folio' => $acta->Folio
                    ];
                });

            return response()->json([
                'success' => true,
                'actas' => $actas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener actas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Buscar actas para móvil
     */
    public function searchActas(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            if (empty($query)) {
                return response()->json([
                    'success' => true,
                    'actas' => []
                ]);
            }

            $actas = Acta::with(['persona', 'sacramento', 'ermita'])
                ->whereHas('persona', function($q) use ($query) {
                    $q->where('nombre', 'LIKE', "%{$query}%")
                      ->orWhere('paterno', 'LIKE', "%{$query}%")
                      ->orWhere('materno', 'LIKE', "%{$query}%");
                })
                ->orWhere('Libro', 'LIKE', "%{$query}%")
                ->orWhere('Folio', 'LIKE', "%{$query}%")
                ->orderBy('fecha', 'desc')
                ->take(10)
                ->get()
                ->map(function($acta) {
                    return [
                        'id' => $acta->cve_actas,
                        'tipo' => $acta->sacramento->nombre ?? 'Sin tipo',
                        'persona' => $acta->persona ? 
                            $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno . ' ' . $acta->persona->apellido_materno : 
                            'Sin persona',
                        'fecha' => $acta->fecha,
                        'ermita' => $acta->ermita->nombre ?? 'Sin ermita',
                        'libro' => $acta->Libro,
                        'folio' => $acta->Folio
                    ];
                });

            return response()->json([
                'success' => true,
                'actas' => $actas
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error en búsqueda: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar si ya existe un acta duplicada (AJAX)
     */
    public function verificarDuplicado(Request $request)
    {
        try {
            $tipoSacramento = $request->input('tipo_sacramento');
            $personaId = $request->input('persona_id');
            $fechaSacramento = $request->input('fecha_sacramento');

            if (!$tipoSacramento || !$personaId || !$fechaSacramento) {
                return response()->json([
                    'duplicado' => false,
                    'mensaje' => 'Datos insuficientes para verificar'
                ]);
            }

            $existeActa = false;
            $mensaje = '';

            switch ($tipoSacramento) {
                case 'Matrimonio':
                    $existeActa = Acta::where('tipo_sacramento', 'Matrimonio')
                        ->where(function($query) use ($personaId) {
                            $query->where('persona_id', $personaId)
                                  ->orWhere('persona_2_id', $personaId);
                        })
                        ->exists();
                    $mensaje = $existeActa ? 'Esta persona ya tiene un acta de matrimonio registrada.' : '';
                    break;

                case 'Bautismo':
                    $existeActa = Acta::where('tipo_sacramento', 'Bautismo')
                        ->where('persona_id', $personaId)
                        ->exists();
                    $mensaje = $existeActa ? 'Esta persona ya tiene un acta de bautismo registrada.' : '';
                    break;

                case 'Confirmación':
                    $existeActa = Acta::where('tipo_sacramento', 'Confirmación')
                        ->where('persona_id', $personaId)
                        ->exists();
                    $mensaje = $existeActa ? 'Esta persona ya tiene un acta de confirmación registrada.' : '';
                    break;
            }

            return response()->json([
                'duplicado' => $existeActa,
                'mensaje' => $mensaje
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'duplicado' => false,
                'mensaje' => 'Error al verificar duplicados: ' . $e->getMessage()
            ], 500);
        }
    }
}

