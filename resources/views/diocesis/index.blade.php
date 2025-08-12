@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<div class="row">
    <div class="col-md-12">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Diócesis</h2>
                <br></br>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDiocesis">
                        AGREGAR
                    </button>
                </div>
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Obispo</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Dirección</th>
                                <th class="actions-column header">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($diocesis as $diocesi)
                            <tr>
                                <td data-label="ID">{{$diocesi->cve_diocesis}}</td>
                                <td data-label="Obispo">
                                    @if($diocesi->obispo)
                                        {{$diocesi->obispo->nombre_obispo}}
                                        {{$diocesi->obispo->apellido_paterno}}
                                        {{$diocesi->obispo->apellido_materno}}
                                    @else
                                        Sin obispo asignado
                                    @endif
                                </td>
                                <td data-label="Nombre">{{$diocesi->nombre}} </td>
                                <td data-label="Dirección">{{$diocesi->direccion_diocesis}}</td>
                                <td data-label="Acciones" class="actions-column">
                                    <div class="btn-group-compact">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editDiocesis{{$diocesi->cve_diocesis}}">
                                            Editar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDiocesis{{$diocesi->cve_diocesis}}">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('diocesis.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('diocesis.create')
            </div>
        </div>
    </div>
</div>

@endsection
