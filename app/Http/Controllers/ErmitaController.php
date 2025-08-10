<?php

namespace App\Http\Controllers;

use App\Models\Ermita;
use App\Models\Parroquia;
use App\Models\Municipio;
use Illuminate\Http\Request;

class ErmitaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ermitas = Ermita::with('parroquia', 'municipio')->get();
        $parroquias = Parroquia::all();
        $municipios = Municipio::all();
        return view('ermitas.index', compact('ermitas', 'parroquias', 'municipios'));
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
        $maxId = Ermita::max('cve_ermitas') ?? 0;
        $nextId = $maxId + 1;
        
        $ermita = new Ermita;
        $ermita->cve_ermitas = $nextId;
        $ermita->cve_parroquia = $request->input('cve_parroquia');
        $ermita->cve_municipio = $request->input('cve_municipio');
        $ermita->nombre_ermita = $request->input('nombre');
        $ermita->direccion = $request->input('direccion');
        $ermita->save();

        return redirect()->route('ermitas.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ermita = Ermita::findOrFail($id);
        $parroquias = Parroquia::all();
        $municipios = Municipio::all();
        return view('ermitas.edit', compact('ermita', 'parroquias', 'municipios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ermita = Ermita::findOrFail($id);
        $ermita->cve_parroquia = $request->input('cve_parroquia');
        $ermita->cve_municipio = $request->input('cve_municipio');
        $ermita->nombre_ermita = $request->input('nombre');
        $ermita->direccion = $request->input('direccion');
        $ermita->save();

        return redirect()->route('ermitas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $ermita = Ermita::findOrFail($id);
            $ermita->delete();
            
            return redirect()->route('ermitas.index')->with('success', 'Ermita eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->route('ermitas.index')->with('error', 'No se puede eliminar la ermita porque está siendo utilizada en otros registros.');
            }
            return redirect()->route('ermitas.index')->with('error', 'Error al eliminar la ermita: ' . $e->getMessage());
        }
    }

    /**
     * Get location data for the specified ermita.
     */
    public function getLocation(Ermita $ermita)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $ermita->cve_ermitas,
                'nombre' => $ermita->nombre_ermita,
                'latitude' => $ermita->latitude,
                'longitude' => $ermita->longitude,
                'direccion' => $ermita->direccion
            ]
        ]);
    }

    /**
     * Save location data for the specified ermita.
     */
    public function saveLocation(Request $request, Ermita $ermita)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $ermita->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ubicación guardada exitosamente',
            'data' => [
                'id' => $ermita->cve_ermitas,
                'nombre' => $ermita->nombre_ermita,
                'latitude' => $ermita->latitude,
                'longitude' => $ermita->longitude,
                'direccion' => $ermita->direccion
            ]
        ]);
    }
}
