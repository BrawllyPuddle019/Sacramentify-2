<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matrimonio;
use App\Models\Persona;
use App\Models\Sacramento;
use App\Models\Obispo;
use App\Models\Sacerdote;
use App\Models\Ermita;
use Barryvdh\DomPDF\Facade\Pdf;

class MatrimonioController extends Controller
{
    

    public function generarPDFMatrimonio($id)
    {
        $matrimonio = Matrimonio::findOrFail($id);
        
        $persona = $matrimonio->persona;
        $persona1 = $matrimonio->persona1;
        $padre = $matrimonio->padre;
        $madre = $matrimonio->madre;
        $padre1 = $matrimonio->padre1;
        $madre1 = $matrimonio->madre1;
        $padrino = $matrimonio->padrino;
        $madrina = $matrimonio->madrina;
        $sacerdote = $matrimonio->sacerdote;
        $obispo = $matrimonio->obispo;
        $ermita = $matrimonio->ermita;
    
        $data = [
            'matrimonio' => $matrimonio,
            'persona' => $persona,
            'persona1' => $persona1,
            'padre' => $padre,
            'madre' => $madre,
            'padre1' => $padre1,
            'madre1' => $madre1,
            'padrino' => $padrino,
            'madrina' => $madrina,
            'sacerdote' => $sacerdote,
            'obispo' => $obispo,
            'ermita' => $ermita,
        ];
    
        $pdf = PDF::loadView('matrimonios.pdf', $data);
    
        $nombreArchivo = 'matrimonio_';
        if ($persona && $persona1) {
            $nombreArchivo .= $persona->nombre . '_' . $persona1->nombre;
        } else {
            $nombreArchivo .= 'constancia';
        }
        $nombreArchivo .= '.pdf';
    
        return $pdf->download($nombreArchivo);
    }
 
    public function index(Request $request)
{
    $matrimonios = Matrimonio::query();

    if ($request->filled('esposo')) {
        $esposo = $request->input('esposo');
        $matrimonios->whereHas('persona', function ($query) use ($esposo) {
            $query->where('nombre', 'like', "%{$esposo}%")
                ->orWhere('paterno', 'like', "%{$esposo}%")
                ->orWhere('materno', 'like', "%{$esposo}%");
        });
    }

    if ($request->filled('esposa')) {
        $esposa = $request->input('esposa');
        $matrimonios->whereHas('persona1', function ($query) use ($esposa) {
            $query->where('nombre', 'like', "%{$esposa}%")
                ->orWhere('paterno', 'like', "%{$esposa}%")
                ->orWhere('materno', 'like', "%{$esposa}%");
        });
    }

    if ($request->filled('fecha')) {
        $fecha = $request->input('fecha');
        $matrimonios->where('fecha', $fecha);
    }

    if ($request->filled('ermita')) {
        $ermita = $request->input('ermita');
        $matrimonios->where('cve_ermitas', $ermita);
    }

    if ($request->filled('sacerdote')) {
        $sacerdote = $request->input('sacerdote');
        $matrimonios->where('cve_sacerdotes', $sacerdote);
    }

    $matrimonios = $matrimonios->with(['persona', 'persona1', 'padre', 'madre', 'padre1', 'madre1', 'padrino', 'madrina', 'sacerdote', 'obispo', 'ermita'])->get();

    $personas = Persona::all();
    $sacramentos = Sacramento::all();
    $sacerdotes = Sacerdote::all();
    $obispos = Obispo::all();
    $ermitas = Ermita::all();

    return view('matrimonios.index', compact('matrimonios', 'personas', 'sacramentos', 'sacerdotes', 'obispos', 'ermitas'));
}

    public function create()
    {
        // ...
    }

    public function store(Request $request)
    {
        $matrimonio = new Matrimonio;
        $matrimonio->cve_persona = $request->input('cve_persona');
        $matrimonio->cve_persona1 = $request->input('cve_persona1');
        $matrimonio->Per_cve_padre = $request->input('Per_cve_padre');
        $matrimonio->Per_cve_madre = $request->input('Per_cve_madre');
        $matrimonio->Per_cve_padre1 = $request->input('Per_cve_padre1');
        $matrimonio->Per_cve_madre1 = $request->input('Per_cve_madre1');
        $matrimonio->libro = $request->input('libro');
        $matrimonio->foja = $request->input('foja');
        $matrimonio->cve_ermitas = $request->input('cve_ermitas');
        $matrimonio->Per_cve_padrino = $request->input('Per_cve_padrino');
        $matrimonio->Per_cve_madrina = $request->input('Per_cve_madrina');
        $matrimonio->fecha = $request->input('fecha');
        $matrimonio->cve_sacramentos = $request->input('cve_sacramentos');
        $matrimonio->cve_sacerdotes = $request->input('cve_sacerdotes');
        $matrimonio->cve_obispos = $request->input('cve_obispos');
        $matrimonio->save();

        return redirect()->route('matrimonios.index');
    }

    public function edit($id)
    {
        $matrimonio = Matrimonio::findOrFail($id);
        $personas = Persona::all();
        $ermitas = Ermita::all();

        return view('matrimonios.edit', compact('matrimonio', 'personas', 'ermitas'));
    }

    public function update(Request $request, $id)
    {
        $matrimonio = Matrimonio::findOrFail($id);
        $matrimonio->cve_persona = $request->input('cve_persona');
        $matrimonio->cve_persona1 = $request->input('cve_persona1');
        $matrimonio->Per_cve_padre = $request->input('Per_cve_padre');
        $matrimonio->Per_cve_madre = $request->input('Per_cve_madre');
        $matrimonio->Per_cve_padre1 = $request->input('Per_cve_padre1');
        $matrimonio->Per_cve_madre1 = $request->input('Per_cve_madre1');
        $matrimonio->libro = $request->input('libro');
        $matrimonio->foja = $request->input('foja');
        $matrimonio->cve_ermitas = $request->input('cve_ermitas');
        $matrimonio->Per_cve_padrino = $request->input('Per_cve_padrino');
        $matrimonio->Per_cve_madrina = $request->input('Per_cve_madrina');
        $matrimonio->fecha = $request->input('fecha');
        $matrimonio->cve_sacerdotes = $request->input('cve_sacerdotes');
        $matrimonio->cve_obispos = $request->input('cve_obispos');
        $matrimonio->cve_sacramentos = $request->input('cve_sacramentos');
        $matrimonio->save();

        return redirect()->route('matrimonios.index');
    }

    public function destroy($id)
    {
        $matrimonio = Matrimonio::findOrFail($id);
        $matrimonio->delete();

        return redirect()->route('matrimonios.index');
    }
}
