<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados=Estado::all();
        return view('municipios.index', compact('estados'));
        //
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
        $maxId = Estado::max('cve_estado') ?? 0;
        $nextId = $maxId + 1;
        
        $estados = new Estado;

        // Asignar los valores de los campos
        $estados->cve_estado = $nextId;
        $estados->nombre_estado = $request->input('nombre');

        // Guardar el objeto en la base de datos
        $estados->save();

        // Redirigir a la página de índice de personas
        return redirect()->route('municipios.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Estado $estado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $estados = Estado::findOrFail($id);
        return view('municipios.edit', compact('estados'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estado $estado)
    {
        $estado->nombre_estado = $request->input('nombre');
        $estado->save();
        return redirect()->route('municipios.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estado $estado)
    {
        $estado->delete();
        return redirect()->route('municipios.index');
    }
}
