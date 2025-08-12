@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<div class="row">
    <div class="col-md-12">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Pláticas</h2>
                <br></br>
                @if(auth()->user() && auth()->user()->is_admin)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createPlatica">
                        AGREGAR
                    </button>
                </div>
                @endif
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Padre/Padrino</th>
                                <th scope="col">Madre/Madrina</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Fecha</th>
                                @if(auth()->user() && auth()->user()->is_admin)
                                <th class="actions-column header">ACCIONES</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($platicas as $platica)
                            <tr>
                                <td data-label="ID">{{$platica->id}}</td>
                                <td>{{$platica->personaPadre ? $platica->personaPadre->nombre . ' ' . $platica->personaPadre->apellido_paterno . ' ' . $platica->personaPadre->apellido_materno : 'Sin padre'}}</td>
                                <td>{{$platica->personaMadre ? $platica->personaMadre->nombre . ' ' . $platica->personaMadre->apellido_paterno . ' ' . $platica->personaMadre->apellido_materno : 'Sin madre'}}</td>
                                <td>{{$platica->nombre}}</td>
                                <td>{{$platica->fecha}}</td>
                                @if(auth()->user() && auth()->user()->is_admin)
                                <td>
                                    <button style="color: #ffffff;" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editPlatica{{$platica->id}}">
                                        Editar
                                    </button>
                                    <button style="color: #ffffff;" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePlatica{{$platica->id}}">
                                        Eliminar
                                    </button>
                                </td>
                                @endif
                            </tr>
                            @include('platicas.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('platicas.create')
            </div>
        </div>
    </div>
</div>

@endsection
