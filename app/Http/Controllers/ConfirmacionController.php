<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Confirmacion;
use App\Models\Persona;
use App\Models\Sacramento;
use App\Models\Obispo;
use App\Models\Sacerdote;
use Barryvdh\DomPDF\Facade\Pdf;

class ConfirmacionController extends Controller
{   

    public function generarPDFConfirmacion($id)
  {
    $confirmacion = Confirmacion::findOrFail($id);
    
    $persona = $confirmacion->persona;
    $padre = $confirmacion->padre;
    $madre = $confirmacion->madre;
    $padrino = $confirmacion->padrino;
    $madrina = $confirmacion->madrina;
    $sacerdote = $confirmacion->sacerdote;
    $obispo = $confirmacion->obispo;

    $data = [
        'confirmacion' => $confirmacion,
        'persona' => $persona,
        'padre' => $padre,
        'madre' => $madre,
        'padrino' => $padrino,
        'madrina' => $madrina,
        'sacerdote' => $sacerdote,
        'obispo' => $obispo,
    ];

    $pdf = PDF::loadView('confirmaciones.pdf', $data);

    return $pdf->download('confirmacion_' . $persona->nombre . '.pdf');
  }



    public function index()
    {
        $confirmaciones = Confirmacion::all();
        $personas = Persona::all();
        $sacramentos = Sacramento::all();
        $sacerdotes = Sacerdote::all();
        $obispos = Obispo::all();

        return view('confirmaciones.index', compact('confirmaciones', 'personas', 'sacramentos', 'sacerdotes', 'obispos'));
    }

    public function create()
    {
        // ...
    }

    public function store(Request $request)
    {
        $confirmacion = new Confirmacion;
        $confirmacion->cve_persona = $request->input('cve_persona');
        $confirmacion->Per_cve_padre = $request->input('Per_cve_padre');
        $confirmacion->Per_cve_madre = $request->input('Per_cve_madre');
        $confirmacion->Per_cve_padrino = $request->input('Per_cve_padrino');
        $confirmacion->Per_cve_madrina = $request->input('Per_cve_madrina');
        $confirmacion->fecha = $request->input('fecha');
        $confirmacion->cve_sacramentos = $request->input('cve_sacramentos');
        $confirmacion->cve_sacerdotes = $request->input('cve_sacerdotes');
        $confirmacion->cve_obispos = $request->input('cve_obispos');
        $confirmacion->save();

        return redirect()->route('confirmaciones.index');
    }

    public function edit($id)
    {
        $confirmacion = Confirmacion::findOrFail($id);
        $personas = Persona::all();

        return view('confirmaciones.edit', compact('confirmacion', 'personas'));
    }

    public function update(Request $request, $id)
    {
        $confirmacion = Confirmacion::findOrFail($id);
        $confirmacion->cve_persona = $request->input('cve_persona');
        $confirmacion->Per_cve_padre = $request->input('Per_cve_padre');
        $confirmacion->Per_cve_madre = $request->input('Per_cve_madre');
        $confirmacion->Per_cve_padrino = $request->input('Per_cve_padrino');
        $confirmacion->Per_cve_madrina = $request->input('Per_cve_madrina');
        $confirmacion->fecha = $request->input('fecha');
        $confirmacion->cve_sacerdotes = $request->input('cve_sacerdotes');
        $confirmacion->cve_obispos = $request->input('cve_obispos');
        $confirmacion->cve_sacramentos = $request->input('cve_sacramentos');
        $confirmacion->save();

        return redirect()->route('confirmaciones.index');
    }

    public function destroy($id)
    {
        $confirmacion = Confirmacion::findOrFail($id);
        $confirmacion->delete();

        return redirect()->route('confirmaciones.index');
    }
}

