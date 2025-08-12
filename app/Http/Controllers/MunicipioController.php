<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\Estado;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $municipios = Municipio::with('estado')->get();
        $estados = Estado::all();
        return view('municipios.index', compact('municipios', 'estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener el siguiente ID disponible
        $maxId = Municipio::max('cve_municipio') ?? 0;
        $nextId = $maxId + 1;
        
        $municipio = new Municipio;
        $municipio->cve_municipio = $nextId;
        $municipio->nombre_municipio = $request->input('nombre_municipio');
        $municipio->cve_estado = $request->input('cve_estado');
        $municipio->save();
        
        return redirect()->route('municipios.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipio $municipio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $municipios = Municipio::findOrFail($id);
        $estados = Estado::all();
        return view('municipios.edit', compact('municipios', 'estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Municipio $municipio)
    {
        $municipio->nombre_municipio = $request->input('nombre_municipio');
        $municipio->cve_estado = $request->input('cve_estado');
        $municipio->save();
        
        return redirect()->route('municipios.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Municipio $municipio)
    {
        // Verificar si el municipio tiene personas relacionadas
        $personasRelacionadas = \App\Models\Persona::where('cve_municipio', $municipio->cve_municipio)->count();
        
        if ($personasRelacionadas > 0) {
            return redirect()->route('municipios.index')->with('error', 
                "⚠️ No se puede eliminar el municipio '{$municipio->nombre}' porque tiene {$personasRelacionadas} persona(s) relacionada(s). 👥 Reasigne o elimine primero las personas correspondientes.");
        }
        
        $municipio->delete();
        
        return redirect()->route('municipios.index')->with('success', 
            "✅ Municipio '{$municipio->nombre}' eliminado correctamente. 🏘️");
    }
}
   