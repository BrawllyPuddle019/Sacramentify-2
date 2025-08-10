@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<div class="row">
    <div class="col-md-12">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Sacramentos</h2>
                <br></br>
                @if(auth()->user() && auth()->user()->is_admin)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSacramento">
                        AGREGAR
                    </button>
                </div>
                @endif
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Descripción</th>
                                @if(auth()->user() && auth()->user()->is_admin)
                                <th class="actions-column header">ACCIONES</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sacramentos as $sacramento)
                            <tr>
                                <td data-label="ID">{{$sacramento->cve_sacramentos}}</td>
                                <td data-label="Nombre">{{$sacramento->nombre}}</td>
                                <td data-label="Descripción">{{$sacramento->descripcion}}</td>
                                @if(auth()->user() && auth()->user()->is_admin)
                                <td data-label="Acciones" class="actions-column">
                                    <div class="btn-group-compact">
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editSacramento{{$sacramento->cve_sacramentos}}">
                                            Editar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSacramento{{$sacramento->cve_sacramentos}}">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @include('sacramentos.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('sacramentos.create')
            </div>
        </div>
    </div>
</div>

@endsection
