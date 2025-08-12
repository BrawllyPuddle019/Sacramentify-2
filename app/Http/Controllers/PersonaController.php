<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Persona;
use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Models\Estado;

class PersonaController extends Controller
{

    public function generarPDF($id)
    {
        $persona = Persona::findOrFail($id);
    
        $pdf = Pdf::loadView('personas.pdf', compact('persona'));
    
        return $pdf->download('persona_' . $persona->nombre . '.pdf');
    }
   
    public function index(Request $request)
{
    $nombre = $request->input('nombre');
    $municipio = $request->input('municipio');
    $sexo = $request->input('sexo');

    $personas = Persona::query()
        ->when($nombre, function ($query, $nombre) {
            $query->where(function ($query) use ($nombre) {
                $query->where('nombre', 'like', "%{$nombre}%")
                    ->orWhere('apellido_paterno', 'like', "%{$nombre}%")
                    ->orWhere('apellido_materno', 'like', "%{$nombre}%");
            });
        })
        ->when($municipio, function ($query, $municipio) {
            $query->whereHas('municipio', function ($query) use ($municipio) {
                $query->where('nombre_municipio', 'like', "%{$municipio}%");
            });
        })
        ->when($sexo, function ($query, $sexo) {
            $query->where('sexo', $sexo);
        })
        ->with('municipio')
        ->get();

    $municipios = Municipio::all();

    return view('personas.index', compact('personas', 'municipios'));
}



    public function create()
   {
   }

    public function store(Request $request)
{
    // Obtener el siguiente ID disponible
    $maxId = Persona::max('cve_persona') ?? 0;
    $nextId = $maxId + 1;
    
    $persona = new Persona;

    // Asignar los valores de los campos
    $persona->cve_persona = $nextId;
    $persona->nombre = $request->input('nombre');
    $persona->apellido_paterno = $request->input('paterno');
    $persona->apellido_materno = $request->input('materno');
    $persona->fecha_nacimiento = $request->input('fecha_nacimiento');
    $persona->direccion = $request->input('direccion');
    $persona->cve_municipio = $request->input('cve_municipio');
    $persona->sexo = $request->input('sexo');
    $persona->telefono = $request->input('telefono');

    // Guardar el objeto en la base de datos
    $persona->save();

    // Redirigir a la página de índice de personas
    return redirect()->route('personas.index');
}

    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        $municipios = Municipio::all();
        return view('personas.edit', compact('persona', 'municipios'));
    }

    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);
        $persona->nombre = $request->input('nombre');
        $persona->apellido_paterno = $request->input('paterno');
        $persona->apellido_materno = $request->input('materno');
        $persona->fecha_nacimiento = $request->input('fecha_nacimiento');
        $persona->direccion = $request->input('direccion');
        $persona->cve_municipio = $request->input('cve_municipio');
        $persona->sexo = $request->input('sexo');
        $persona->telefono = $request->input('telefono');
        $persona->save();
        return redirect()->route('personas.index');
    }
    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);
        
        // Verificar si la persona tiene actas relacionadas
        $actasRelacionadas = 0;
        $roles = [];
        
        // Verificar todas las posibles relaciones con actas
        $actasComoPrincipal = \App\Models\Acta::where('cve_persona', $id)->count();
        if ($actasComoPrincipal > 0) {
            $actasRelacionadas += $actasComoPrincipal;
            $roles[] = "persona principal ($actasComoPrincipal)";
        }
        
        $actasComoSegunda = \App\Models\Acta::where('cve_persona2', $id)->count();
        if ($actasComoSegunda > 0) {
            $actasRelacionadas += $actasComoSegunda;
            $roles[] = "segunda persona ($actasComoSegunda)";
        }
        
        $actasComoPadrino = \App\Models\Acta::where('Per_cve_padrino', $id)->count();
        if ($actasComoPadrino > 0) {
            $actasRelacionadas += $actasComoPadrino;
            $roles[] = "padrino ($actasComoPadrino)";
        }
        
        $actasComoPadrino1 = \App\Models\Acta::where('Per_cve_padrino1', $id)->count();
        if ($actasComoPadrino1 > 0) {
            $actasRelacionadas += $actasComoPadrino1;
            $roles[] = "padrino/testigo ($actasComoPadrino1)";
        }
        
        $actasComoMadrina = \App\Models\Acta::where('Per_cve_madrina', $id)->count();
        if ($actasComoMadrina > 0) {
            $actasRelacionadas += $actasComoMadrina;
            $roles[] = "madrina ($actasComoMadrina)";
        }
        
        $actasComoMadrina1 = \App\Models\Acta::where('Per_cve_madrina1', $id)->count();
        if ($actasComoMadrina1 > 0) {
            $actasRelacionadas += $actasComoMadrina1;
            $roles[] = "madrina/testigo ($actasComoMadrina1)";
        }
        
        $actasComoPadre = \App\Models\Acta::where('Per_cve_padre', $id)->count();
        if ($actasComoPadre > 0) {
            $actasRelacionadas += $actasComoPadre;
            $roles[] = "padre ($actasComoPadre)";
        }
        
        $actasComoPadre1 = \App\Models\Acta::where('Per_cve_padre1', $id)->count();
        if ($actasComoPadre1 > 0) {
            $actasRelacionadas += $actasComoPadre1;
            $roles[] = "padre alternativo ($actasComoPadre1)";
        }
        
        $actasComoMadre = \App\Models\Acta::where('Per_cve_madre', $id)->count();
        if ($actasComoMadre > 0) {
            $actasRelacionadas += $actasComoMadre;
            $roles[] = "madre ($actasComoMadre)";
        }
        
        $actasComoMadre1 = \App\Models\Acta::where('Per_cve_madre1', $id)->count();
        if ($actasComoMadre1 > 0) {
            $actasRelacionadas += $actasComoMadre1;
            $roles[] = "madre alternativa ($actasComoMadre1)";
        }
        
        if ($actasRelacionadas > 0) {
            $rolesTexto = implode(', ', $roles);
            return redirect()->route('personas.index')->with('error', 
                "⚠️ No se puede eliminar la persona '{$persona->nombre} {$persona->apellido_paterno}' porque tiene {$actasRelacionadas} acta(s) relacionada(s) como: {$rolesTexto}. 📜 Elimine primero las actas correspondientes.");
        }
        
        $persona->delete();
        return redirect()->route('personas.index')->with('success', 
            "✅ Persona '{$persona->nombre} {$persona->apellido_paterno}' eliminada correctamente. 👤");
    }
}
