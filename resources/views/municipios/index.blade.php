@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<div class="row">
    <div class="col-md-6">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Municipios</h2>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createM">
                        AGREGAR
                    </button>
                </div>
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Nombre</th>
                                <th class="actions-column header">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($municipios as $municipio)
                            <tr>
                                <td data-label="ID">{{$municipio->cve_municipio}}</td>
                                <td data-label="Estado">{{$municipio->estado ? $municipio->estado->nombre : 'Sin estado'}}</td>
                                <td data-label="Nombre">{{$municipio->nombre}}</td>
                                <td data-label="Acciones" class="actions-column">
                                    <div class="btn-group-compact">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editM{{$municipio->cve_municipio}}">
                                            Editar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteM{{$municipio->cve_municipio}}">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('municipios.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('municipios.create')
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Estados</h2>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createE">
                        AGREGAR
                    </button>
                </div>
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th class="actions-column header">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($estados as $estado)
                            <tr>
                                <td data-label="ID">{{$estado->cve_estado}}</td>
                                <td data-label="Nombre">{{$estado->nombre_estado}}</td>
                                <td data-label="Acciones" class="actions-column">
                                    <div class="btn-group-compact">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editE{{$estado->cve_estado}}">
                                            Editar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteE{{$estado->cve_estado}}">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('estados.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('estados.create')
            </div>
        </div>
    </div>
</div>

@endsection
