<?php

namespace App\Http\Controllers;

use App\Models\Sacramento;
use Illuminate\Http\Request;

class SacramentoController extends Controller
{
    public function index()
    {
        $sacramentos = Sacramento::all();
        return view('sacramentos.index', compact('sacramentos'));
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $sacramento = new Sacramento;
        $sacramento->nombre_sacramento = $request->input('nombre');
        $sacramento->descripcion = $request->input('descripcion');
        $sacramento->save();

        return redirect()->route('sacramentos.index');
    }

    public function edit($id)
    {
        $sacramento = Sacramento::findOrFail($id);
        return view('sacramentos.edit', compact('sacramento'));
    }

    public function update(Request $request, Sacramento $sacramento)
    {
        $sacramento->nombre_sacramento = $request->input('nombre');
        $sacramento->descripcion = $request->input('descripcion');
        $sacramento->save();

        return redirect()->route('sacramentos.index');
    }

    public function destroy(Sacramento $sacramento)
    {
        // Verificar si el sacramento tiene actas relacionadas
        $actasRelacionadas = \App\Models\Acta::where('tipo_acta', $sacramento->cve_sacramentos)->count();
        
        if ($actasRelacionadas > 0) {
            return redirect()->route('sacramentos.index')->with('error', 
                "⚠️ No se puede eliminar el sacramento '{$sacramento->nombre}' porque tiene {$actasRelacionadas} acta(s) relacionada(s). 📜 Elimine primero las actas correspondientes.");
        }
        
        $sacramento->delete();

        return redirect()->route('sacramentos.index')->with('success', 
            "✅ Sacramento '{$sacramento->nombre}' eliminado correctamente. ✨");
    }
}
