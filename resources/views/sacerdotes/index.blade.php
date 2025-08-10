@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<!-- Mensajes de éxito y error -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Sacerdotes</h2>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createS">
                        AGREGAR
                    </button>
                </div>
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Diócesis</th>
                                <th class="actions-column header">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sacerdotes as $sacerdote)
                            <tr>
                                <td data-label="ID">{{$sacerdote->cve_sacerdotes}}</td>
                                <td data-label="Nombre">{{$sacerdote->nombre_sacerdote}} {{$sacerdote->apellido_paterno}} {{$sacerdote->apellido_materno}}</td>
                                <td data-label="Diócesis">{{ $sacerdote->diocesi ? $sacerdote->diocesi->nombre_diocesis : 'Sin diócesis' }}</td>
                                <td data-label="Acciones" class="actions-column">
                                    <div class="btn-group-compact">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editS{{$sacerdote->cve_sacerdotes}}">
                                            Editar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteS{{$sacerdote->cve_sacerdotes}}">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('sacerdotes.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('sacerdotes.create')
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Obispos</h2>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOb">
                        AGREGAR
                    </button>
                </div>
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Diócesis</th>
                                <th class="actions-column header">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obispos as $obispo)
                            <tr>
                                <td data-label="ID">{{$obispo->cve_obispos}}</td>
                                <td data-label="Nombre">{{$obispo->nombre_obispo}} {{$obispo->apellido_paterno}} {{$obispo->apellido_materno}}</td>
                                <td data-label="Diócesis">{{ $obispo->diocesi ? $obispo->diocesi->nombre_diocesis : 'Sin diócesis' }}</td>
                                <td data-label="Acciones" class="actions-column">
                                    <div class="btn-group-compact">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editOb{{$obispo->cve_obispos}}">
                                            Editar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteOb{{$obispo->cve_obispos}}">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('obispos.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('obispos.create')
            </div>
        </div>
    </div>
</div>

@endsection
