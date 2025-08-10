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
        $persona->delete();
        return redirect()->route('personas.index');
    }
}
