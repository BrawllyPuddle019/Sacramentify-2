<?php

namespace App\Http\Controllers;

use App\Models\Diocesi;
use App\Models\Obispo;
use Illuminate\Http\Request;

class DiocesiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diocesis = Diocesi::with('obispo')->get();
        $obispos = Obispo::all();
        return view('diocesis.index', compact('diocesis', 'obispos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener el siguiente ID disponible
        $maxId = Diocesi::max('cve_diocesis') ?? 0;
        $nextId = $maxId + 1;
        
        $diocesis = new Diocesi;
        $diocesis->cve_diocesis = $nextId;
        $diocesis->nombre_diocesis = $request->input('nombre');
        $diocesis->direccion_diocesis = $request->input('direccion');
        $diocesis->save();

        return redirect()->route('diocesis.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $diocesis = Diocesi::findOrFail($id);
        $obispos = Obispo::all();
        return view('diocesis.edit', compact('diocesis', 'obispos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $diocesis = Diocesi::findOrFail($id);
        $diocesis->nombre_diocesis = $request->input('nombre');
        $diocesis->direccion_diocesis = $request->input('direccion');
        $diocesis->save();

        return redirect()->route('diocesis.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $diocesis = Diocesi::findOrFail($id);
        
        // Verificar si la diócesis tiene parroquias relacionadas
        $parroquiasRelacionadas = \App\Models\Parroquia::where('cve_diocesis', $diocesis->cve_diocesis)->count();
        
        if ($parroquiasRelacionadas > 0) {
            return redirect()->route('diocesis.index')->with('error', 
                "⚠️ No se puede eliminar la diócesis '{$diocesis->nombre}' porque tiene {$parroquiasRelacionadas} parroquia(s) relacionada(s). ⛪ Reasigne o elimine primero las parroquias correspondientes.");
        }
        
        $diocesis->delete();

        return redirect()->route('diocesis.index')->with('success', 
            "✅ Diócesis '{$diocesis->nombre}' eliminada correctamente. 🏛️");
    }
}
