<?php

namespace App\Http\Controllers;

use App\Models\Obispo;


use Illuminate\Http\Request;

class ObispoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obispos = Obispo::with('diocesi')->get();
        return view('sacerdotes.index', compact('obispos'));
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
        $maxId = Obispo::max('cve_obispos') ?? 0;
        $nextId = $maxId + 1;
        
        $obispos = new Obispo;
        $obispos->cve_obispos = $nextId;
        $obispos->nombre_obispo = $request->input('nombre');
        $obispos->apellido_paterno = $request->input('paterno');
        $obispos->apellido_materno = $request->input('materno');
        $obispos->cve_diocesis = $request->input('cve_diocesis');
        $obispos->save();

        return redirect()->route('sacerdotes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Obispo $obispo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $obispos = Obispo::findOrFail($id);
        return view('sacerdotes.edit', compact('obispos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $obispos = Obispo::findOrFail($id);
        $obispos->nombre_obispo = $request->input('nombre');
        $obispos->apellido_paterno = $request->input('paterno');
        $obispos->apellido_materno = $request->input('materno');
        $obispos->cve_diocesis = $request->input('cve_diocesis');
        $obispos->save();

        return redirect()->route('sacerdotes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $obispos = Obispo::findOrFail($id);
        
        // Verificar si el obispo tiene actas como celebrante
        $actasComoCelebrante = \App\Models\Acta::where('cve_obispos_celebrante', $id)->count();
        
        if ($actasComoCelebrante > 0) {
            return redirect()->route('sacerdotes.index')->with('error', 
                "⚠️ No se puede eliminar el obispo '{$obispos->nombre}' porque tiene {$actasComoCelebrante} acta(s) como celebrante. 📜 Reasigne o elimine primero las actas correspondientes.");
        }
        
        $obispos->delete();

        return redirect()->route('sacerdotes.index')->with('success', 
            "✅ Obispo '{$obispos->nombre}' eliminado correctamente. 👨‍💼");
    }
}
