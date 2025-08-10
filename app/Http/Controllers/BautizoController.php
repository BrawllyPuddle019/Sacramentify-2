<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bautizo;
use App\Models\Persona;
use App\Models\Sacramento;
use App\Models\Obispo;
use App\Models\Sacerdote;

use Barryvdh\DomPDF\Facade\Pdf;

class BautizoController extends Controller
{
    public function generarPDFBautizo($id)
    {
    $bautizo = Bautizo::findOrFail($id);
    $persona = $bautizo->persona;
    $padre = $bautizo->padre;
    $madre = $bautizo->madre;
    $padrino = $bautizo->padrino;
    $madrina = $bautizo->madrina;
    $sacerdote = $bautizo->sacerdote;
    $obispo = $bautizo->obispo;

    $data = [
        'bautizo' => $bautizo,
        'persona' => $persona,
        'padre' => $padre,
        'madre' => $madre,
        'padrino' => $padrino,
        'madrina' => $madrina,
        'sacerdote' => $sacerdote,
        'obispo' => $obispo,
    ];

    $pdf = Pdf::loadView('bautizos.pdf', $data);
    return $pdf->download('bautizo_' . $persona->nombre . '.pdf');
  }
    

  public function index(Request $request)
  {
      $bautizos = Bautizo::query();
  
      if ($request->filled('persona')) {
          $persona = $request->input('persona');
          $bautizos->whereHas('persona', function ($query) use ($persona) {
              $query->where('nombre', 'like', "%{$persona}%")
                  ->orWhere('paterno', 'like', "%{$persona}%")
                  ->orWhere('materno', 'like', "%{$persona}%");
          });
      }
  
      if ($request->filled('padre')) {
          $padre = $request->input('padre');
          $bautizos->whereHas('padre', function ($query) use ($padre) {
              $query->where('nombre', 'like', "%{$padre}%")
                  ->orWhere('paterno', 'like', "%{$padre}%")
                  ->orWhere('materno', 'like', "%{$padre}%");
          });
      }
  
      if ($request->filled('madre')) {
          $madre = $request->input('madre');
          $bautizos->whereHas('madre', function ($query) use ($madre) {
              $query->where('nombre', 'like', "%{$madre}%")
                  ->orWhere('paterno', 'like', "%{$madre}%")
                  ->orWhere('materno', 'like', "%{$madre}%");
          });
      }
  
      if ($request->filled('fecha_desde')) {
          $fechaDesde = $request->input('fecha_desde');
          $bautizos->where('fecha', '>=', $fechaDesde);
      }
  
      if ($request->filled('fecha_hasta')) {
          $fechaHasta = $request->input('fecha_hasta');
          $bautizos->where('fecha', '<=', $fechaHasta);
      }
  
      $bautizos = $bautizos->with(['persona', 'padre', 'madre', 'padrino', 'madrina', 'sacerdote', 'obispo'])->get();
  
      $personas = Persona::all();
      $sacramentos = Sacramento::all();
      $sacerdotes = Sacerdote::all();
      $obispos = Obispo::all();
  
      return view('bautizos.index', compact('bautizos', 'personas', 'sacramentos', 'sacerdotes', 'obispos'));
  }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $bautizo = new Bautizo;
        $bautizo->cve_persona = $request->input('cve_persona');
        $bautizo->Per_cve_padre = $request->input('Per_cve_padre');
        $bautizo->Per_cve_madre = $request->input('Per_cve_madre');
        $bautizo->Per_cve_padrino = $request->input('Per_cve_padrino');
        $bautizo->Per_cve_madrina = $request->input('Per_cve_madrina');
        $bautizo->fecha = $request->input('fecha');
        $bautizo->cve_sacramentos = $request->input('cve_sacramentos');
        $bautizo->cve_sacerdotes = $request->input('cve_sacerdotes');
        $bautizo->cve_obispos = $request->input('cve_obispos');
        $bautizo->save();

        return redirect()->route('bautizos.index');
    }

    public function edit($id)
    {
        $bautizo = Bautizo::findOrFail($id);
        $personas = Persona::all();
        return view('bautizos.edit', compact('bautizo', 'personas'));
    }

    public function update(Request $request, $id)
    {
        $bautizo = Bautizo::findOrFail($id);
        $bautizo->cve_persona = $request->input('cve_persona');
        $bautizo->Per_cve_padre = $request->input('Per_cve_padre');
        $bautizo->Per_cve_madre = $request->input('Per_cve_madre');
        $bautizo->Per_cve_padrino = $request->input('Per_cve_padrino');
        $bautizo->Per_cve_madrina = $request->input('Per_cve_madrina');
        $bautizo->fecha = $request->input('fecha');
        $bautizo->cve_sacerdotes = $request->input('cve_sacerdotes');
        $bautizo->cve_obispos = $request->input('cve_obispos');
        $bautizo->cve_sacramentos = $request->input('cve_sacramentos');
        $bautizo->save();

        return redirect()->route('bautizos.index');
    }

    public function destroy($id)
    {
        $bautizo = Bautizo::findOrFail($id);
        $bautizo->delete();

        return redirect()->route('bautizos.index');
    }
}


