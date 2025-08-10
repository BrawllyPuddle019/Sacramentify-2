@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ __('Detalles del Acta') }}</h3>
                    <div>
                        <a href="{{ route('actas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="{{ route('actas.pdf', $acta->cve_actas) }}" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- Información General --}}
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Información General</h5>
                            
                            <div class="mb-3">
                                <strong>Tipo de Acta:</strong>
                                <span class="text-muted">{{ $acta->tipoActa ? $acta->tipoActa->nombre : 'No especificado' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Fecha:</strong>
                                <span class="text-muted">{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : 'No especificada' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Libro:</strong>
                                <span class="text-muted">{{ $acta->Libro ?? 'No especificado' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Fojas:</strong>
                                <span class="text-muted">{{ $acta->Fojas ?? 'No especificado' }}</span>
                            </div>

                            <div class="mb-3">
                                <strong>Folio:</strong>
                                <span class="text-muted">{{ $acta->Folio ?? 'No especificado' }}</span>
                            </div>
                        </div>

                        {{-- Personas Involucradas --}}
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-users"></i> Personas Involucradas</h5>
                            
                            @if($acta->persona)
                                <div class="mb-3">
                                    <strong>Persona Principal:</strong>
                                    <span class="text-muted">
                                        {{ $acta->persona->nombre }} {{ $acta->persona->apellido_paterno }} {{ $acta->persona->apellido_materno }}
                                    </span>
                                </div>
                            @endif

                            @if($acta->persona2)
                                <div class="mb-3">
                                    <strong>Segunda Persona:</strong>
                                    <span class="text-muted">
                                        {{ $acta->persona2->nombre }} {{ $acta->persona2->apellido_paterno }} {{ $acta->persona2->apellido_materno }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        {{-- Oficiantes --}}
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-cross"></i> Oficiantes</h5>
                            
                            @if($acta->sacerdoteCelebrante)
                                <div class="mb-3">
                                    <strong>Sacerdote Celebrante:</strong>
                                    <span class="text-muted">
                                        {{ $acta->sacerdoteCelebrante->nombre_sacerdote }} {{ $acta->sacerdoteCelebrante->apellido_paterno }} {{ $acta->sacerdoteCelebrante->apellido_materno }}
                                    </span>
                                </div>
                            @endif

                            @if($acta->sacerdoteAsistente)
                                <div class="mb-3">
                                    <strong>Sacerdote Asistente:</strong>
                                    <span class="text-muted">
                                        {{ $acta->sacerdoteAsistente->nombre_sacerdote }} {{ $acta->sacerdoteAsistente->apellido_paterno }} {{ $acta->sacerdoteAsistente->apellido_materno }}
                                    </span>
                                </div>
                            @endif

                            @if($acta->obispoCelebrante)
                                <div class="mb-3">
                                    <strong>Obispo Celebrante:</strong>
                                    <span class="text-muted">
                                        {{ $acta->obispoCelebrante->nombre_obispo }} {{ $acta->obispoCelebrante->apellido_paterno }} {{ $acta->obispoCelebrante->apellido_materno }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Lugar --}}
                        <div class="col-md-6">
                            <h5 class="mb-3"><i class="fas fa-church"></i> Lugar</h5>
                            
                            @if($acta->ermita)
                                <div class="mb-3">
                                    <strong>Ermita:</strong>
                                    <span class="text-muted">{{ $acta->ermita->nombre_ermita }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($acta->observaciones)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3"><i class="fas fa-notes-medical"></i> Observaciones</h5>
                                <p class="text-muted">{{ $acta->observaciones }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Información del Sistema --}}
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted mb-2">Información del Sistema</h6>
                            <small class="text-muted">
                                <strong>ID del Acta:</strong> {{ $acta->cve_actas }} |
                                <strong>Número Consecutivo:</strong> {{ $acta->numero_consecutivo ?? 'No asignado' }} |
                                <strong>Creada:</strong> {{ $acta->created_at ? $acta->created_at->format('d/m/Y H:i') : 'No disponible' }} |
                                <strong>Actualizada:</strong> {{ $acta->updated_at ? $acta->updated_at->format('d/m/Y H:i') : 'No disponible' }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
