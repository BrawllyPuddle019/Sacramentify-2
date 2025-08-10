@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">
<div class="row">
    <div class="col-md-12">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Matrimonios</h2>
                <br></br>
                @if(auth()->user() && auth()->user()->is_admin)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createMatrimonio">
                        AGREGAR
                    </button>
                </div>
                @endif
                <form action="{{ route('matrimonios.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="esposo" class="form-control" placeholder="Buscar por nombre del esposo" value="{{ request('esposo') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="esposa" class="form-control" placeholder="Buscar por nombre de la esposa" value="{{ request('esposa') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="fecha" class="form-control" placeholder="Buscar por fecha" value="{{ request('fecha') }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            
                        </div>
                        <div class="col-md-4">
                            
                        </div>
                        <div class="col-md-4 d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                            <a href="{{ route('matrimonios.index') }}" class="btn btn-secondary">Reiniciar</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col" style="width: 5%;">ID</th>
                                <th scope="col" style="width: 10%;">Esposo</th>
                                <th scope="col" style="width: 10%;">Esposa</th>
                                <th scope="col" style="width: 10%;">Padre del Esposo</th>
                                <th scope="col" style="width: 10%;">Madre del Esposo</th>
                                <th scope="col" style="width: 10%;">Padre de la Esposa</th>
                                <th scope="col" style="width: 10%;">Madre de la Esposa</th>
                                <th scope="col" style="width: 5%;">Libro</th>
                                <th scope="col" style="width: 5%;">Foja</th>
                                <th scope="col" style="width: 10%;">Ermita</th>
                                <th scope="col" style="width: 10%;">Padrino</th>
                                <th scope="col" style="width: 10%;">Madrina</th>
                                <th scope="col" style="width: 10%;">Fecha</th>
                                <th scope="col" style="width: 10%;">Sacerdote</th>
                                <th scope="col" style="width: 10%;">Obispo</th>
                                <th scope="col" style="width: 10%;">Tipo de Acta</th>
                                <th scope="col" style="width: 10%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matrimonios as $matrimonio)
                            <tr>
                                <td data-label="ID">{{ $matrimonio->id }}</td>
                                <td>{{ $matrimonio->persona->nombre }} {{ $matrimonio->persona->paterno }} {{ $matrimonio->persona->materno}}</td>
                                <td>{{ $matrimonio->persona1->nombre }} {{ $matrimonio->persona1->paterno }} {{ $matrimonio->persona1->materno }}</td>
                                <td>{{ $matrimonio->padre->nombre }} {{ $matrimonio->padre->paterno }} {{ $matrimonio->padre->materno }}</td>
                                <td>{{ $matrimonio->madre->nombre }} {{ $matrimonio->madre->paterno }} {{ $matrimonio->madre->materno }}</td>
                                <td>{{ $matrimonio->padre1->nombre }} {{ $matrimonio->padre1->paterno }} {{ $matrimonio->padre1->materno }}</td>
                                <td>{{ $matrimonio->madre1->nombre }} {{ $matrimonio->madre1->paterno }} {{ $matrimonio->madre1->materno }}</td>
                                <td>{{ $matrimonio->libro }}</td>
                                <td>{{ $matrimonio->foja }}</td>
                                <td>{{ $matrimonio->ermita->nombre }}</td>
                                <td>{{ $matrimonio->padrino->nombre }} {{ $matrimonio->padrino->paterno }} {{ $matrimonio->padrino->materno }}</td>
                                <td>{{ $matrimonio->madrina->nombre }} {{ $matrimonio->madrina->paterno }} {{ $matrimonio->madrina->materno }}</td>
                                <td>{{ $matrimonio->fecha }}</td>
                                <td>{{ $matrimonio->sacerdote ? $matrimonio->sacerdote->nombre : 'N/A' }} {{ $matrimonio->sacerdote ? $matrimonio->sacerdote->paterno : 'N/A' }} {{ $matrimonio->sacerdote ? $matrimonio->sacerdote->materno : 'N/A' }}</td>
                                <td>{{ $matrimonio->obispo ? $matrimonio->obispo->nombre : 'N/A' }} {{ $matrimonio->obispo ? $matrimonio->obispo->paterno : 'N/A' }} {{ $matrimonio->obispo ? $matrimonio->obispo->materno : 'N/A' }}</td>
                                <td>{{ $matrimonio->sacramento->nombre }}</td>
                                <td>
                                    @if(auth()->user() && auth()->user()->is_admin)
                                    <button style="color: #ffffff;" type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editMatrimonio{{ $matrimonio->id }}">
                                        Editar
                                    </button>
                                    <button style="color: #ffffff;" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteMatrimonio{{ $matrimonio->id }}">
                                        Eliminar
                                    </button>
                                    @endif
                                    <a href="{{ route('matrimonios.pdf', $matrimonio->id) }}" class="btn btn-primary" target="_blank">
                                        Descargar PDF
                                    </a>
                                </td>
                            </tr>
                            @include('matrimonios.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('matrimonios.create')
            </div>
        </div>
    </div>
</div>
@endsection