@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<div class="row">
    <div class="col-md-12">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Confirmaciones</h2>
                <br></br>
                @if(auth()->user() && auth()->user()->is_admin)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createConfirmacion">
                        AGREGAR
                    </button>
                </div>
                @endif
                <form action="{{ route('confirmaciones.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="persona" class="form-control" placeholder="Buscar por nombre de persona" value="{{ request('persona') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="padre" class="form-control" placeholder="Buscar por nombre del padre" value="{{ request('padre') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="madre" class="form-control" placeholder="Buscar por nombre de la madre" value="{{ request('madre') }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <input type="date" name="fecha_desde" class="form-control" placeholder="Fecha desde" value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="fecha_hasta" class="form-control" placeholder="Fecha hasta" value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="col-md-4 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2">Buscar</button>
                            <a href="{{ route('confirmaciones.index') }}" class="btn btn-secondary">Reiniciar</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive table-container animate__animated animate__fadeIn">
                    <table class="table table-bordered table-hover align-middle table-compact">
                        <thead class="table-dark text-white">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Persona</th>
                                <th scope="col">Padre</th>
                                <th scope="col">Madre</th>
                                <th scope="col">Padrino</th>
                                <th scope="col">Madrina</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Sacerdote</th>
                                <th scope="col">Obispo</th>
                                <th scope="col">Sacramento</th>
                                <th class="actions-column header">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($confirmaciones as $confirmacion)
                            <tr>
                                <td data-label="ID">{{ $confirmacion->id }}</td>
                                <td data-label="Persona">{{ $confirmacion->persona->nombre }} {{ $confirmacion->persona->paterno }} {{ $confirmacion->persona->materno }}</td>
                                <td data-label="Padre">{{ $confirmacion->padre->nombre }} {{ $confirmacion->padre->paterno }} {{ $confirmacion->padre->materno }}</td>
                                <td data-label="Madre">{{ $confirmacion->madre->nombre }} {{ $confirmacion->madre->paterno }} {{ $confirmacion->madre->materno }}</td>
                                <td data-label="Padrino">{{ $confirmacion->padrino->nombre }} {{ $confirmacion->padrino->paterno }} {{ $confirmacion->padrino->materno }}</td>
                                <td data-label="Madrina">{{ $confirmacion->madrina->nombre }} {{ $confirmacion->madrina->paterno }} {{ $confirmacion->madrina->materno }}</td>
                                <td data-label="Fecha">{{ $confirmacion->fecha }}</td>
                                <td data-label="Sacerdote">{{ $confirmacion->sacerdote ? $confirmacion->sacerdote->nombre : 'N/A' }} {{ $confirmacion->sacerdote ? $confirmacion->sacerdote->paterno : 'N/A' }} {{ $confirmacion->sacerdote ? $confirmacion->sacerdote->materno : 'N/A' }}</td>
                                <td data-label="Obispo">{{ $confirmacion->obispo ? $confirmacion->obispo->nombre : 'N/A' }} {{ $confirmacion->obispo ? $confirmacion->obispo->paterno : 'N/A' }} {{ $confirmacion->obispo ? $confirmacion->obispo->materno : 'N/A' }}</td>
                                <td data-label="Sacramento">{{ $confirmacion->sacramento->nombre }}</td>
                                <td data-label="Acciones" class="actions-column">
                                    <div class="btn-group-compact">
                                        @if(auth()->user() && auth()->user()->is_admin)
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editConfirmacion{{ $confirmacion->id }}">
                                            Editar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmacion{{ $confirmacion->id }}">
                                            Eliminar
                                        </button>
                                        @endif
                                        <a href="{{ route('confirmaciones.pdf', $confirmacion->id) }}" class="btn btn-primary" target="_blank">
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @include('confirmaciones.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('confirmaciones.create')
            </div>
        </div>
    </div>
</div>
@endsection