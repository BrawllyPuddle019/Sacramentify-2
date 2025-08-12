<?php

namespace App\Http\Controllers;

use App\Models\Sacerdote;
use App\Models\Obispo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SacerdoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sacerdotes = Sacerdote::with('diocesi')->get();
        $obispos = Obispo::with('diocesi')->get();
        $diocesis = \App\Models\Diocesi::all();
        return view('sacerdotes.index', compact('sacerdotes', 'obispos', 'diocesis'));
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
        $maxId = Sacerdote::max('cve_sacerdotes') ?? 0;
        $nextId = $maxId + 1;
        
        $sacerdotes = new Sacerdote;
        $sacerdotes->cve_sacerdotes = $nextId;
        $sacerdotes->nombre_sacerdote = $request->input('nombre');
        $sacerdotes->apellido_paterno = $request->input('paterno');
        $sacerdotes->apellido_materno = $request->input('materno');
        $sacerdotes->cve_diocesis = $request->input('cve_diocesis');
        $sacerdotes->save();

        return redirect()->route('sacerdotes.index');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Sacerdote $sacerdotes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $sacerdotes = Sacerdote::findOrFail($id);
        return view('sacerdotes.edit', compact('sacerdotes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $sacerdotes = Sacerdote::findOrFail($id);
        $sacerdotes->nombre_sacerdote = $request->input('nombre');
        $sacerdotes->apellido_paterno = $request->input('paterno');
        $sacerdotes->apellido_materno = $request->input('materno');
        $sacerdotes->cve_diocesis = $request->input('cve_diocesis');
        $sacerdotes->save();

        return redirect()->route('sacerdotes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $sacerdote = Sacerdote::findOrFail($id);
            
            // Verificar si el sacerdote tiene actas asociadas
            $actasCount = DB::table('actas')
                ->where('cve_sacerdotes_celebrante', $id)
                ->orWhere('cve_sacerdotes_asistente', $id)
                ->count();
            
            if ($actasCount > 0) {
                return redirect()->route('sacerdotes.index')
                    ->with('error', "❌ No se puede eliminar el sacerdote {$sacerdote->nombre_sacerdote} {$sacerdote->apellido_paterno} porque tiene {$actasCount} acta" . ($actasCount > 1 ? 's' : '') . " asociada" . ($actasCount > 1 ? 's' : '') . ". Para eliminar este sacerdote, primero debes reasignar o eliminar las actas relacionadas.");
            }
            
            $nombreCompleto = $sacerdote->nombre_sacerdote . ' ' . $sacerdote->apellido_paterno;
            $sacerdote->delete();
            
            return redirect()->route('sacerdotes.index')
                ->with('success', "✅ Sacerdote {$nombreCompleto} eliminado exitosamente.");
                
        } catch (\Exception $e) {
            return redirect()->route('sacerdotes.index')
                ->with('error', 'Error al eliminar el sacerdote: ' . $e->getMessage());
        }
    }
}
