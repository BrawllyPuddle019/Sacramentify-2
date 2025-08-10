<?php

namespace App\Http\Controllers;

use App\Models\Platica;
use App\Models\Persona;
use Illuminate\Http\Request;

class PlaticaController extends Controller
{
    public function index()
    {
        $platicas = Platica::with('personaPadre', 'personaMadre')->get();
        $personas = Persona::all();
        $hombres = Persona::where('sexo', '1')->get(); // Masculino
        $mujeres = Persona::where('sexo', '0')->get(); // Femenino
        return view('platicas.index', compact('platicas', 'personas', 'hombres', 'mujeres'));
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $platica = new Platica;
        $platica->padre = $request->input('padre');
        $platica->madre = $request->input('madre');
        $platica->nombre = $request->input('nombre');
        $platica->fecha = $request->input('fecha');
        $platica->save();

        return redirect()->route('platicas.index');
    }

    public function edit($id)
    {
        $platica = Platica::findOrFail($id);
        $personas = Persona::all();
        $hombres = Persona::where('sexo', '1')->get(); // Masculino
        $mujeres = Persona::where('sexo', '0')->get(); // Femenino
        return view('platicas.edit', compact('platica', 'personas', 'hombres', 'mujeres'));
    }

    public function update(Request $request, $id)
    {
        $platica = Platica::findOrFail($id);
        $platica->padre = $request->input('padre');
        $platica->madre = $request->input('madre');
        $platica->nombre = $request->input('nombre');
        $platica->fecha = $request->input('fecha');
        $platica->save();

        return redirect()->route('platicas.index');
    }

    public function destroy($id)
    {
        $platica = Platica::findOrFail($id);
        $platica->delete();

        return redirect()->route('platicas.index');
    }
}
