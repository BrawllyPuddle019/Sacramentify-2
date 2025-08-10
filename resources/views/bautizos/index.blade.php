@extends('layouts.app')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<div class="row">
    <div class="col-md-12">
        <div class="card animate__animated animate__fadeIn">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Bautizos</h2>
                <br></br>
                @if(auth()->user() && auth()->user()->is_admin)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createB">
                        AGREGAR
                    </button>
                </div>
                @endif
                <form action="{{ route('bautizos.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="persona" class="form-control" placeholder="Buscar por nombre de persona" value="{{ request('persona') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="padre" class="form-control" placeholder="Buscar por nombre de padre" value="{{ request('padre') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="madre" class="form-control" placeholder="Buscar por nombre de madre" value="{{ request('madre') }}">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <input type="date" name="fecha_desde" class="form-control" placeholder="Fecha desde" value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="date" name="fecha_hasta" class="form-control" placeholder="Fecha hasta" value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="col-md-4 d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">Buscar</button>
                            <a href="{{ route('bautizos.index') }}" class="btn btn-secondary">Reiniciar</a>
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
                            @foreach ($bautizos as $bautizo)
                            <tr>
                                <td data-label="ID">{{$bautizo->id}}</td>
                                <td data-label="Persona">{{$bautizo->persona->nombre}} {{$bautizo->persona->paterno}} {{$bautizo->persona->materno}}</td>
                                <td data-label="Padre">{{$bautizo->padre->nombre}} {{$bautizo->padre->paterno}} {{$bautizo->padre->materno}}</td>
                                <td data-label="Madre">{{$bautizo->madre->nombre}} {{$bautizo->madre->paterno}} {{$bautizo->madre->materno}}</td>
                                <td data-label="Padrino">{{$bautizo->padrino->nombre}} {{$bautizo->padrino->paterno}} {{$bautizo->padrino->materno}}</td>
                                <td data-label="Madrina">{{$bautizo->madrina->nombre}} {{$bautizo->madrina->paterno}} {{$bautizo->madrina->materno}}</td>
                                <td data-label="Fecha">{{$bautizo->fecha}}</td>
                                <td data-label="Sacerdote">{{$bautizo->sacerdote ? $bautizo->sacerdote->nombre : 'N/A'}} {{$bautizo->sacerdote ? $bautizo->sacerdote->paterno : 'N/A'}} {{$bautizo->sacerdote ? $bautizo->sacerdote->materno : 'N/A'}}</td>
                                <td data-label="Obispo">{{$bautizo->obispo ? $bautizo->obispo->nombre : 'N/A'}} {{$bautizo->obispo ? $bautizo->obispo->paterno : 'N/A'}} {{$bautizo->obispo ? $bautizo->obispo->materno : 'N/A'}}</td>
                                <td data-label="Sacramento">{{$bautizo->sacramento->nombre}}</td>
                                <td data-label="Acciones" class="actions-column">
                                    <div class="btn-group-compact">
                                        @if(auth()->user() && auth()->user()->is_admin)
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editB{{$bautizo->id}}">
                                            Editar
                                        </button>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteB{{$bautizo->id}}">
                                            Eliminar
                                        </button>
                                        @endif
                                        <a href="{{ route('bautizos.pdf', $bautizo->id) }}" class="btn btn-primary" target="_blank">
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @include('bautizos.info')
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @include('bautizos.create')
            </div>
        </div>
    </div>
</div>
@endsection