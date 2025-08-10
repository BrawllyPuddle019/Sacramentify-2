@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<div class="card animate__animated animate__fadeIn">
    <div class="card-body">
        <h2 class="mb-0 text-center">Tabla de Personas</h2>
        <br></br>
        @if(auth()->user() && auth()->user()->is_admin)
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create">
                AGREGAR
            </button>
        </div>
        @endif
        <form action="{{ route('personas.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre completo" value="{{ request('nombre') }}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="municipio" class="form-control" placeholder="Buscar por municipio" value="{{ request('municipio') }}">
                </div>
                <div class="col-md-2">
                    <select name="sexo" class="form-control">
                        <option value="">Seleccionar Sexo</option>
                        <option value="1" @if(request('sexo') == '1') selected @endif>Masculino</option>
                        <option value="0" @if(request('sexo') == '0') selected @endif>Femenino</option>
                    </select>
                </div>
                <div class="col-md-2 justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a href="{{ route('personas.index') }}" class="btn btn-secondary">Reiniciar</a>
                </div>
            </div>
        </form>

        <div class="table-responsive table-container animate__animated animate__fadeIn" style="max-height: 800px; overflow-y: auto;">
            <table class="table table-bordered table-hover align-middle table-compact">
                <thead class="table-dark text-white">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Fecha Nac.</th>
                        <th scope="col">Sexo</th>
                        <th scope="col">Dirección</th>
                        <th scope="col">Teléfono</th>
                        <th class="actions-column header">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($personas as $persona)
                    <tr>
                        <td data-label="ID">{{$persona->cve_persona}}</td>
                        <td data-label="Municipio">{{ $persona->municipio ? $persona->municipio->nombre_municipio : 'Sin municipio' }}</td>
                        <td data-label="Nombre">{{$persona->nombre}} {{$persona->apellido_paterno}} {{$persona->apellido_materno}}</td>
                        <td data-label="Fecha Nac.">{{$persona->fecha_nacimiento}}</td>
                        <td data-label="Sexo">{{ $persona->sexo == '1' || $persona->sexo == 1 ? 'Masculino' : 'Femenino' }}</td>
                        <td data-label="Dirección">{{$persona->direccion}}</td>
                        <td data-label="Teléfono">{{$persona->telefono}}</td>
                        <td data-label="Acciones" class="actions-column">
                            <div class="btn-group-compact">
                                @if(auth()->user() && auth()->user()->is_admin)
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit{{$persona->cve_persona}}">
                                    Editar
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete{{$persona->cve_persona}}">
                                    Eliminar
                                </button>
                                @endif
                                <a href="{{ route('personas.pdf', $persona->cve_persona) }}" class="btn btn-primary" target="_blank">
                                    PDF
                                </a>
                            </div>
                        </td>
                    </tr>
                    @include('personas.info')
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('personas.create')
    </div>
</div>

@endsection

