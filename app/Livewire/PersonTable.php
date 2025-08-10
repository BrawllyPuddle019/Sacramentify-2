<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Persona;

class PersonTable extends Component
{
    protected $listeners = ['refreshTable' => 'render'];

    public function render()
    {
        $personas = $this->getFilteredPersonas();

        return view('livewire.person-table', [
            'personas' => $personas,
        ]);
    }

    private function getFilteredPersonas()
    {
        $filtros = session('personFiltros', []);

        $personas = Persona::with('municipios')
            ->when(isset($filtros['municipio']), function ($query) use ($filtros) {
                $query->where('municipio_id', $filtros['municipio']);
            })
            ->when(isset($filtros['apellido']), function ($query) use ($filtros) {
                $query->where('paterno', 'like', '%' . $filtros['apellido'] . '%')
                    ->orWhere('materno', 'like', '%' . $filtros['apellido'] . '%');
            })
            ->when(isset($filtros['nombre']), function ($query) use ($filtros) {
                $query->where('nombre', 'like', '%' . $filtros['nombre'] . '%');
            })
            ->when(isset($filtros['fechaNacimiento']), function ($query) use ($filtros) {
                $query->whereDate('fecha_nacimiento', $filtros['fechaNacimiento']);
            })
            ->when(isset($filtros['sexo']), function ($query) use ($filtros) {
                $query->where('sexo', $filtros['sexo']);
            })
            ->get();

        return $personas;
    }
}