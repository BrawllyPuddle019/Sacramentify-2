<?php

namespace App\Http\Controllers;

use App\Models\Parroquia;
use App\Models\Diocesi;
use App\Models\Municipio;
use Illuminate\Http\Request;

class ParroquiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parroquias = Parroquia::with('diocesis', 'municipio')->get();
        $diocesis = Diocesi::all();
        $municipios = Municipio::all();
        return view('parroquias.index', compact('parroquias', 'diocesis', 'municipios'));
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
        $maxId = Parroquia::max('cve_parroquia') ?? 0;
        $nextId = $maxId + 1;
        
        $parroquia = new Parroquia;
        $parroquia->cve_parroquia = $nextId;
        $parroquia->cve_diocesis = $request->input('cve_diocesis');
        $parroquia->cve_municipio = $request->input('cve_municipio');
        $parroquia->nombre_parroquia = $request->input('nombre');
        $parroquia->direccion = $request->input('direccion');
        $parroquia->save();

        return redirect()->route('parroquias.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $parroquia = Parroquia::findOrFail($id);
        $diocesis = Diocesi::all();
        $municipios = Municipio::all();
        return view('parroquias.edit', compact('parroquia', 'diocesis', 'municipios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Parroquia $parroquia)
    {
        $parroquia->cve_diocesis = $request->input('cve_diocesis');
        $parroquia->cve_municipio = $request->input('cve_municipio');
        $parroquia->nombre_parroquia = $request->input('nombre');
        $parroquia->direccion = $request->input('direccion');
        $parroquia->save();

        return redirect()->route('parroquias.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Parroquia $parroquia)
    {
        $parroquia->delete();

        return redirect()->route('parroquias.index');
    }

    /**
     * Get location data for the specified parroquia.
     */
    public function getLocation(Parroquia $parroquia)
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $parroquia->cve_parroquia,
                'nombre' => $parroquia->nombre_parroquia,
                'latitude' => $parroquia->latitude,
                'longitude' => $parroquia->longitude,
                'direccion' => $parroquia->direccion
            ]
        ]);
    }

    /**
     * Save location data for the specified parroquia.
     */
    public function saveLocation(Request $request, Parroquia $parroquia)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $parroquia->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ubicación guardada exitosamente',
            'data' => [
                'id' => $parroquia->cve_parroquia,
                'nombre' => $parroquia->nombre_parroquia,
                'latitude' => $parroquia->latitude,
                'longitude' => $parroquia->longitude,
                'direccion' => $parroquia->direccion
            ]
        ]);
    }
}
