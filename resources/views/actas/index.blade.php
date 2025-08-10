@extends('layouts.app')
@section('content')
<style>
    .table-container {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
        width: 100%;
        max-width: 100%;
    }
    
    .table-responsive {
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
        min-width: 120px;
    }
    
    .table td {
        font-size: 0.875rem;
        line-height: 1.4;
        vertical-align: top;
        padding: 8px 6px;
        min-width: 120px;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Anchos específicos para mejor distribución */
    .table th:nth-child(1), .table td:nth-child(1) { min-width: 60px; max-width: 80px; } /* # */
    .table th:nth-child(2), .table td:nth-child(2) { min-width: 100px; max-width: 120px; } /* Tipo */
    .table th:nth-child(3), .table td:nth-child(3) { min-width: 100px; max-width: 120px; } /* Fecha */
    .table th:nth-child(4), .table td:nth-child(4) { min-width: 200px; max-width: 300px; } /* Personas */
    .table th:nth-child(10), .table td:nth-child(10) { min-width: 140px; max-width: 180px; } /* Acciones */
    
    /* Ajustes responsive más agresivos para sidebar activo */
    @media (min-width: 992px) and (max-width: 1399.98px) {
        .table th, .table td {
            font-size: 0.8rem;
            padding: 6px 4px;
        }
        
        .table th:nth-child(4), .table td:nth-child(4) { 
            min-width: 180px; 
            max-width: 220px; 
        }
    }
    
    @media (min-width: 1200px) and (max-width: 1599.98px) {
        /* Ajustes cuando el sidebar está probablemente activo */
        .table th, .table td {
            font-size: 0.85rem;
            padding: 7px 5px;
        }
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.8em;
        border-radius: 6px;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        border-radius: 4px;
    }
    
    .small {
        font-size: 0.8rem;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .gap-1 {
        gap: 0.25rem;
    }

    /* Responsive Cards for Mobile */
    @media (max-width: 991.98px) {
        .table-responsive {
            display: none;
        }
        .mobile-cards {
            display: block;
        }
    }

    @media (min-width: 992px) {
        .mobile-cards {
            display: none;
        }
        .table-responsive {
            display: block;
        }
    }

    /* Collapsible columns con breakpoints más inteligentes */
    .col-collapsible {
        display: table-cell;
    }
    
    /* Ocultar columnas basado en el espacio disponible */
    @media (max-width: 1599.98px) {
        .col-hide-xxl {
            display: none !important;
        }
    }
    
    @media (max-width: 1399.98px) {
        .col-hide-xl {
            display: none !important;
        }
    }
    
    @media (max-width: 1199.98px) {
        .col-hide-lg {
            display: none !important;
        }
    }

    @media (max-width: 991.98px) {
        .col-hide-md {
            display: none !important;
        }
    }

    /* Detección de sidebar activo - asumiendo que reduce el ancho disponible */
    @media (min-width: 1200px) and (max-width: 1400px) {
        /* Probable sidebar activo, ocultar más columnas */
        .col-hide-sidebar {
            display: none !important;
        }
    }

    /* Mobile card styling */
    .acta-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 1rem;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .acta-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .acta-card-header {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 8px 8px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .acta-card-body {
        padding: 1rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #495057;
        flex: 0 0 40%;
    }

    .info-value {
        flex: 1;
        text-align: right;
    }

    /* Expandable content */
    .expandable-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .expandable-content.expanded {
        max-height: 500px;
    }

    .expand-btn {
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .expand-btn.rotated {
        transform: rotate(180deg);
    }

    /* Compact table styles */
    .table-compact td {
        padding: 6px 4px;
        font-size: 0.8rem;
    }

    .table-compact .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }

    /* Estilos para desbordamiento */
    .table-overflow {
        position: relative;
    }

    .table-overflow::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 20px;
        height: 100%;
        background: linear-gradient(to left, rgba(255,255,255,0.8), transparent);
        pointer-events: none;
        z-index: 1;
    }

    /* Mejorar scrollbar horizontal */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Sticky actions column */
    .sticky-actions {
        position: sticky;
        right: 0;
        background: white;
        z-index: 2;
        box-shadow: -2px 0 4px rgba(0,0,0,0.1);
    }

    /* Truncate long text in cells */
    .text-truncate-cell {
        max-width: 150px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .text-truncate-cell:hover {
        white-space: normal;
        overflow: visible;
        background: #f8f9fa;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        z-index: 3;
        position: relative;
    }
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h2 class="mb-0 text-center">Tabla de Actas</h2>
                <br>

                @if(auth()->user() && auth()->user()->is_admin)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex gap-2">
                        @php $userCredits = auth()->user()->getCredits(); @endphp
                        @if($userCredits->available_credits > 0)
                            <button type="button" class="btn btn-primary" style="background-color: #1061d2; color: #ffffff;" data-bs-toggle="modal" data-bs-target="#createActa">
                                <i class="fas fa-plus"></i> AGREGAR ACTA
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary" disabled title="No tienes créditos suficientes">
                                <i class="fas fa-plus"></i> AGREGAR ACTA
                            </button>
                        @endif
                        <a href="{{ route('actas.trashed') }}" class="btn btn-warning">
                            <i class="fas fa-trash"></i> PAPELERA
                        </a>
                        
                        <!-- Controles de Vista -->
                        <div class="btn-group d-none d-lg-flex" role="group" aria-label="Vista de tabla">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-columns"></i> Columnas
                                </button>
                                <ul class="dropdown-menu">
                                    <li><h6 class="dropdown-header">Mostrar/Ocultar Columnas</h6></li>
                                    <li><a class="dropdown-item" href="#" onclick="toggleColumn('col-hide-lg')">
                                        <i class="fas fa-eye" id="icon-lg"></i> Padrinos/Padres
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="toggleColumn('col-hide-xl')">
                                        <i class="fas fa-eye" id="icon-xl"></i> Lugar/Celebrante
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" onclick="toggleColumnByIndex(8)">
                                        <i class="fas fa-eye-slash" id="icon-registro"></i> Registro
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Indicador de créditos -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge {{ $userCredits->available_credits <= 5 ? 'bg-warning text-dark' : 'bg-success' }}">
                            <i class="fas fa-coins me-1"></i>
                            {{ $userCredits->available_credits }} créditos disponibles
                        </span>
                        @if($userCredits->available_credits <= 5)
                            <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-shopping-cart me-1"></i>Comprar más
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Alerta de créditos bajos -->
                @if($userCredits->available_credits <= 5)
                    <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>¡Atención!</strong> 
                        @if($userCredits->available_credits == 0)
                            No tienes créditos disponibles para crear nuevas actas. 
                            <a href="{{ route('payments.index') }}" class="alert-link">Compra más créditos aquí</a>.
                        @else
                            Te quedan solo {{ $userCredits->available_credits }} créditos. 
                            <a href="{{ route('payments.index') }}" class="alert-link">Considera comprar más</a> para no interrumpir tu trabajo.
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @endif

                <form action="{{ route('actas.index') }}" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="nombre" class="form-control" placeholder="Buscar por nombre" value="{{ request('nombre') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="tipo" class="form-control">
                                <option value="">Todos los tipos</option>
                                <option value="Matrimonio" {{ request('tipo') == 'Matrimonio' ? 'selected' : '' }}>Matrimonio</option>
                                <option value="Bautismo" {{ request('tipo') == 'Bautismo' ? 'selected' : '' }}>Bautismo</option>
                                <option value="Confirmación" {{ request('tipo') == 'Confirmación' ? 'selected' : '' }}>Confirmación</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                            <a href="{{ route('actas.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Reiniciar
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive table-container animate__animated animate__fadeIn" id="tableView">
                    <table class="table table-bordered table-hover align-middle table-compact" id="actasTable">
                        <thead class="table-dark text-white">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Personas</th>
                                <th class="text-center col-hide-lg">Padrinos/Madrinas</th>
                                <th class="text-center col-hide-lg">Padres/Madres</th>
                                <th class="text-center col-hide-xl col-hide-sidebar">Lugar</th>
                                <th class="text-center col-hide-xl col-hide-sidebar">Celebrante</th>
                                <th class="text-center" style="display: none;" id="registro-header">Registro</th>
                                <th class="text-center sticky-actions">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($actas as $index => $acta)
                            <tr>
                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ $actas->firstItem() + $index }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $tipoActa = $acta->tipoActa ? trim($acta->tipoActa->nombre) : '';
                                    @endphp
                                    <span class="badge 
                                        @if($tipoActa == 'Matrimonio') bg-success
                                        @elseif($tipoActa == 'Bautismo') bg-primary
                                        @elseif($tipoActa == 'Confirmación') bg-warning text-dark
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ $tipoActa ?: 'Sin tipo' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : '—' }}
                                </td>
                                <td class="text-truncate-cell">
                                    @if($acta->tipoActa && $acta->tipoActa->nombre == 'Matrimonio')
                                        <div class="mb-1">
                                            <strong>Esposo:</strong><br>
                                            <span title="{{ $acta->persona ? $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno . ' ' . $acta->persona->apellido_materno : '—' }}">
                                                {{ $acta->persona ? $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno . ' ' . $acta->persona->apellido_materno : '—' }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>Esposa:</strong><br>
                                            <span title="{{ $acta->persona2 ? $acta->persona2->nombre . ' ' . $acta->persona2->apellido_paterno . ' ' . $acta->persona2->apellido_materno : '—' }}">
                                                {{ $acta->persona2 ? $acta->persona2->nombre . ' ' . $acta->persona2->apellido_paterno . ' ' . $acta->persona2->apellido_materno : '—' }}
                                            </span>
                                        </div>
                                    @else
                                        <div>
                                            <span title="{{ $acta->persona ? $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno . ' ' . $acta->persona->apellido_materno : '—' }}">
                                                {{ $acta->persona ? $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno . ' ' . $acta->persona->apellido_materno : '—' }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="col-hide-lg">
                                    @if($acta->tipoActa && trim($acta->tipoActa->nombre) == 'Matrimonio')
                                        <div class="mb-1">
                                            <small class="text-muted">Esposo:</small><br>
                                            <strong>Padrino:</strong> {{ $acta->padrino1 ? $acta->padrino1->nombre . ' ' . $acta->padrino1->apellido_paterno . ' ' . $acta->padrino1->apellido_materno : '—' }}<br>
                                            <strong>Madrina:</strong> {{ $acta->madrina1 ? $acta->madrina1->nombre . ' ' . $acta->madrina1->apellido_paterno . ' ' . $acta->madrina1->apellido_materno : '—' }}
                                        </div>
                                        <div>
                                            <small class="text-muted">Esposa:</small><br>
                                            <strong>Padrino:</strong> {{ $acta->padrino ? $acta->padrino->nombre . ' ' . $acta->padrino->apellido_paterno . ' ' . $acta->padrino->apellido_materno : '—' }}<br>
                                            <strong>Madrina:</strong> {{ $acta->madrina ? $acta->madrina->nombre . ' ' . $acta->madrina->apellido_paterno . ' ' . $acta->madrina->apellido_materno : '—' }}
                                        </div>
                                    @elseif($acta->tipoActa && (trim($acta->tipoActa->nombre) == 'Bautismo' || trim($acta->tipoActa->nombre) == 'Confirmación'))
                                        @php
                                            $sexo = $acta->persona ? $acta->persona->sexo : null;
                                        @endphp
                                        @if($sexo == 1 || $sexo == '1' || strtolower($sexo) == 'm')
                                            <strong>Padrino:</strong> {{ $acta->padrino1 ? $acta->padrino1->nombre . ' ' . $acta->padrino1->apellido_paterno . ' ' . $acta->padrino1->apellido_materno : '—' }}<br>
                                            <strong>Madrina:</strong> {{ $acta->madrina1 ? $acta->madrina1->nombre . ' ' . $acta->madrina1->apellido_paterno . ' ' . $acta->madrina1->apellido_materno : '—' }}
                                        @else
                                            <strong>Padrino:</strong> {{ $acta->padrino ? $acta->padrino->nombre . ' ' . $acta->padrino->apellido_paterno . ' ' . $acta->padrino->apellido_materno : '—' }}<br>
                                            <strong>Madrina:</strong> {{ $acta->madrina ? $acta->madrina->nombre . ' ' . $acta->madrina->apellido_paterno . ' ' . $acta->madrina->apellido_materno : '—' }}
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="col-hide-lg">
                                    @if($acta->tipoActa && trim($acta->tipoActa->nombre) == 'Matrimonio')
                                        <div class="mb-1">
                                            <small class="text-muted">Esposo:</small><br>
                                            <strong>Padre:</strong> {{ $acta->padre ? $acta->padre->nombre . ' ' . $acta->padre->paterno . ' ' . $acta->padre->materno : '—' }}<br>
                                            <strong>Madre:</strong> {{ $acta->madre ? $acta->madre->nombre . ' ' . $acta->madre->paterno . ' ' . $acta->madre->materno : '—' }}
                                        </div>
                                        <div>
                                            <small class="text-muted">Esposa:</small><br>
                                            <strong>Padre:</strong> {{ $acta->padre1 ? $acta->padre1->nombre . ' ' . $acta->padre1->paterno . ' ' . $acta->padre1->materno : '—' }}<br>
                                            <strong>Madre:</strong> {{ $acta->madre1 ? $acta->madre1->nombre . ' ' . $acta->madre1->paterno . ' ' . $acta->madre1->materno : '—' }}
                                        </div>
                                    @elseif($acta->tipoActa && (trim($acta->tipoActa->nombre) == 'Bautismo' || trim($acta->tipoActa->nombre) == 'Confirmación'))
                                        @php
                                            $sexo = $acta->persona ? $acta->persona->sexo : null;
                                        @endphp
                                        @if($sexo == 1 || $sexo == '1' || strtolower($sexo) == 'm')
                                            <strong>Padre:</strong> {{ $acta->padre ? $acta->padre->nombre . ' ' . $acta->padre->paterno . ' ' . $acta->padre->materno : '—' }}<br>
                                            <strong>Madre:</strong> {{ $acta->madre ? $acta->madre->nombre . ' ' . $acta->madre->paterno . ' ' . $acta->madre->materno : '—' }}
                                        @else
                                            <strong>Padre:</strong> {{ $acta->padre1 ? $acta->padre1->nombre . ' ' . $acta->padre1->paterno . ' ' . $acta->padre1->materno : '—' }}<br>
                                            <strong>Madre:</strong> {{ $acta->madre1 ? $acta->madre1->nombre . ' ' . $acta->madre1->paterno . ' ' . $acta->madre1->materno : '—' }}
                                        @endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="col-hide-xl col-hide-sidebar">
                                    <div>
                                        <strong>Ermita:</strong><br>
                                        {{ $acta->ermita ? $acta->ermita->nombre_ermita : '—' }}
                                        @if($acta->ermita && $acta->ermita->latitude && $acta->ermita->longitude)
                                            <br>
                                            <div class="mt-1">
                                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $acta->ermita->latitude }},{{ $acta->ermita->longitude }}" 
                                                   class="btn btn-outline-info btn-sm" target="_blank" title="Cómo llegar">
                                                    <i class="fas fa-route"></i> Ubicación
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="col-hide-xl col-hide-sidebar">
                                    <div class="mb-1">
                                        <strong>Sacerdote:</strong><br>
                                        {{ $acta->sacerdoteCelebrante ? $acta->sacerdoteCelebrante->nombre_sacerdote . ' ' . $acta->sacerdoteCelebrante->apellido_paterno . ' ' . $acta->sacerdoteCelebrante->apellido_materno : '—'}}
                                    </div>
                                    @if($acta->sacerdoteAsistente)
                                        <div class="mb-1">
                                            <small class="text-muted">Asistente:</small><br>
                                            {{ $acta->sacerdoteAsistente->nombre_sacerdote . ' ' . $acta->sacerdoteAsistente->apellido_paterno . ' ' . $acta->sacerdoteAsistente->apellido_materno }}
                                        </div>
                                    @endif
                                    @if($acta->obispoCelebrante)
                                        <div>
                                            <small class="text-muted">Obispo:</small><br>
                                            {{ $acta->obispoCelebrante->nombre_obispo . ' ' . $acta->obispoCelebrante->apellido_paterno . ' ' . $acta->obispoCelebrante->apellido_materno }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center registro-cell" style="display: none;">
                                    <div class="small">
                                        <strong>Libro:</strong> {{ $acta->Libro ?? '—' }}<br>
                                        <strong>Fojas:</strong> {{ $acta->Fojas ?? '—' }}<br>
                                        <strong>Folio:</strong> {{ $acta->Folio ?? '—' }}
                                    </div>
                                </td>
                                <td class="text-center sticky-actions">
                                    <div class="d-flex flex-column gap-1">
                                        @if(auth()->user() && auth()->user()->is_admin)
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editActa{{ $acta->cve_actas }}">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteActa{{ $acta->cve_actas }}">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        @endif
                                        <a href="{{ route('actas.pdf', $acta->cve_actas) }}" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-file-pdf"></i> PDF
                                        </a>
                                        <button class="btn btn-success btn-sm" onclick="openEmailModal({{ $acta->cve_actas }})" title="Enviar por Email">
                                            <i class="fas fa-envelope"></i> Email
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @include('actas.info', ['acta' => $acta])
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="alert alert-warning mb-0">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>No se encontraron registros.</strong><br>
                                        <small>Intenta cambiar los filtros de búsqueda o agregar un nuevo registro.</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Vista de Cards para Móviles -->
                <div class="mobile-cards">
                    @forelse ($actas as $index => $acta)
                        <div class="acta-card">
                            <div class="acta-card-header">
                                <div>
                                    <span class="badge bg-light text-dark me-2">#{{ $actas->firstItem() + $index }}</span>
                                    @php
                                        $tipoActa = $acta->tipoActa ? trim($acta->tipoActa->nombre) : '';
                                    @endphp
                                    <span class="badge 
                                        @if($tipoActa == 'Matrimonio') bg-success
                                        @elseif($tipoActa == 'Bautismo') bg-primary
                                        @elseif($tipoActa == 'Confirmación') bg-warning text-dark
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ $tipoActa ?: 'Sin tipo' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="acta-card-body">
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-calendar-alt text-primary"></i> Fecha:
                                    </span>
                                    <span class="info-value">
                                        {{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : '—' }}
                                    </span>
                                </div>
                                
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-user text-info"></i> Persona(s):
                                    </span>
                                    <span class="info-value">
                                        @if($acta->tipoActa && $acta->tipoActa->nombre == 'Matrimonio')
                                            <strong>Esposo:</strong><br>
                                            <small>{{ $acta->persona ? $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno . ' ' . $acta->persona->apellido_materno : '—' }}</small><br>
                                            <strong>Esposa:</strong><br>
                                            <small>{{ $acta->persona2 ? $acta->persona2->nombre . ' ' . $acta->persona2->apellido_paterno . ' ' . $acta->persona2->apellido_materno : '—' }}</small>
                                        @else
                                            <small>{{ $acta->persona ? $acta->persona->nombre . ' ' . $acta->persona->apellido_paterno . ' ' . $acta->persona->apellido_materno : '—' }}</small>
                                        @endif
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-church text-warning"></i> Ermita:
                                    </span>
                                    <span class="info-value">
                                        <small>{{ $acta->ermita ? $acta->ermita->nombre_ermita : '—' }}</small>
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="fas fa-user-tie text-success"></i> Celebrante:
                                    </span>
                                    <span class="info-value">
                                        <small>{{ $acta->sacerdoteCelebrante ? $acta->sacerdoteCelebrante->nombre_sacerdote . ' ' . $acta->sacerdoteCelebrante->apellido_paterno : '—'}}</small>
                                    </span>
                                </div>

                                <!-- Información expandible -->
                                <div class="text-center mt-3">
                                    <button class="btn btn-sm btn-outline-secondary expand-btn" onclick="toggleExpand({{ $acta->cve_actas }})">
                                        <i class="fas fa-chevron-down" id="icon-{{ $acta->cve_actas }}"></i> Más detalles
                                    </button>
                                </div>

                                <div class="expandable-content" id="details-{{ $acta->cve_actas }}">
                                    <hr>
                                    <div class="info-row">
                                        <span class="info-label">Registro:</span>
                                        <span class="info-value">
                                            <small>Libro: {{ $acta->Libro ?? '—' }} | Fojas: {{ $acta->Fojas ?? '—' }} | Folio: {{ $acta->Folio ?? '—' }}</small>
                                        </span>
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="mt-3 text-center">
                                    <div class="btn-group" role="group">
                                        @if(auth()->user() && auth()->user()->is_admin)
                                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#editActa{{ $acta->cve_actas }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteActa{{ $acta->cve_actas }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('actas.pdf', $acta->cve_actas) }}" class="btn btn-primary btn-sm" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#emailActa{{ $acta->cve_actas }}">
                                            <i class="fas fa-envelope"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('actas.info', ['acta' => $acta])
                    @empty
                        <div class="text-center py-4">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>No se encontraron registros.</strong><br>
                                <small>Intenta cambiar los filtros de búsqueda o agregar un nuevo registro.</small>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $actas->links() }}
                </div>
                
                <!-- Contador de registros -->
                <div class="mt-3 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Mostrando {{ $actas->firstItem() ?? 0 }} - {{ $actas->lastItem() ?? 0 }} de {{ $actas->total() }} registros
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para envío por email -->
<div class="modal fade" id="emailModal" tabindex="-1" aria-labelledby="emailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailModalLabel">
                    <i class="fas fa-envelope text-success"></i> Enviar Acta Sacramental por Email
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="emailForm">
                <div class="modal-body">
                    <div id="emailLoading" class="text-center" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Cargando información del acta...</p>
                    </div>
                    
                    <div id="emailContent" style="display: none;">
                        <!-- Información del acta -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> Información del Acta a Enviar</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong><i class="fas fa-church"></i> Sacramento:</strong> <span id="actaSacramento">-</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-user"></i> Persona:</strong> <span id="actaPersona">-</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-calendar"></i> Fecha:</strong> <span id="actaFecha">-</span>
                                </div>
                                <div class="col-md-6">
                                    <strong><i class="fas fa-book"></i> Libro/Folio:</strong> <span id="actaLibroFolio">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de envío -->
                        <div class="mb-3">
                            <label for="recipientEmail" class="form-label">
                                <i class="fas fa-at"></i> Email del destinatario *
                            </label>
                            <input type="email" class="form-control" id="recipientEmail" name="recipient_email" 
                                   placeholder="ejemplo@correo.com" required>
                            <div class="invalid-feedback" id="emailError"></div>
                        </div>

                        <div class="mb-3">
                            <label for="emailMessage" class="form-label">
                                <i class="fas fa-comment"></i> Mensaje personalizado (opcional)
                            </label>
                            <textarea class="form-control" id="emailMessage" name="message" rows="4" 
                                      placeholder="Escriba un mensaje personalizado que se incluirá en el email..."></textarea>
                            <div class="form-text">Máximo 1000 caracteres</div>
                        </div>

                        <!-- Vista previa -->
                        <div class="alert alert-light">
                            <h6><i class="fas fa-eye"></i> Vista previa del envío:</h6>
                            <ul class="mb-0">
                                <li><strong><i class="fas fa-envelope-open"></i> Para:</strong> <span id="previewEmail">-</span></li>
                                <li><strong><i class="fas fa-tag"></i> Asunto:</strong> Acta de <span id="previewSacramento">-</span> - <span id="previewPersona">-</span></li>
                                <li><strong><i class="fas fa-paperclip"></i> Adjunto:</strong> Documento PDF del acta sacramental</li>
                                <li><strong><i class="fas fa-shield-alt"></i> Tipo:</strong> Documento oficial certificado</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success" id="sendEmailBtn" disabled>
                        <i class="fas fa-paper-plane"></i> Enviar Email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

#emailModal .alert-info {
    background-color: #e8f4fd;
    border-color: #bee5eb;
    color: #0c5460;
}

#emailModal .alert-light {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>

<script>
let currentActaId = null;

function openEmailModal(actaId) {
    currentActaId = actaId;
    
    // Mostrar modal y loading
    const modal = new bootstrap.Modal(document.getElementById('emailModal'));
    modal.show();
    
    // Mostrar loading
    document.getElementById('emailLoading').style.display = 'block';
    document.getElementById('emailContent').style.display = 'none';
    
    // Cargar información del acta
    fetch(`/actas/${actaId}/email/form`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Llenar información del acta
                document.getElementById('actaSacramento').textContent = data.acta.sacramento;
                document.getElementById('actaPersona').textContent = data.acta.persona;
                document.getElementById('actaFecha').textContent = data.acta.fecha;
                document.getElementById('actaLibroFolio').textContent = data.acta.libro_folio;
                
                // Llenar vista previa
                document.getElementById('previewSacramento').textContent = data.acta.sacramento;
                document.getElementById('previewPersona').textContent = data.acta.persona;
                
                // Mostrar contenido
                document.getElementById('emailLoading').style.display = 'none';
                document.getElementById('emailContent').style.display = 'block';
                
                // Focus en el campo email
                document.getElementById('recipientEmail').focus();
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Error al cargar la información del acta.', 'error');
        });
}

// Actualizar vista previa cuando se escribe el email
document.getElementById('recipientEmail').addEventListener('input', function() {
    const email = this.value;
    document.getElementById('previewEmail').textContent = email || '-';
    
    // Habilitar/deshabilitar botón de envío
    const sendBtn = document.getElementById('sendEmailBtn');
    const isValid = email && this.checkValidity();
    sendBtn.disabled = !isValid;
    
    // Limpiar errores
    this.classList.remove('is-invalid');
    document.getElementById('emailError').textContent = '';
});

// Manejar envío del formulario
document.getElementById('emailForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentActaId) {
        Swal.fire('Error', 'No se ha seleccionado un acta.', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('recipient_email', document.getElementById('recipientEmail').value);
    formData.append('message', document.getElementById('emailMessage').value);
    
    // Agregar token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    }
    
    // Deshabilitar botón y mostrar loading
    const sendBtn = document.getElementById('sendEmailBtn');
    const originalText = sendBtn.innerHTML;
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
    
    // Enviar email
    fetch(`/actas/${currentActaId}/email/send`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('¡Éxito!', data.message, 'success');
            
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('emailModal')).hide();
            
            // Limpiar formulario
            document.getElementById('emailForm').reset();
            document.getElementById('previewEmail').textContent = '-';
        } else {
            // Manejar errores de validación
            if (data.errors) {
                const emailField = document.getElementById('recipientEmail');
                if (data.errors.recipient_email) {
                    emailField.classList.add('is-invalid');
                    document.getElementById('emailError').textContent = data.errors.recipient_email[0];
                }
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Error al enviar el email. Por favor, intenta de nuevo.', 'error');
    })
    .finally(() => {
        // Restaurar botón
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalText;
    });
});

// Limpiar modal al cerrarse
document.getElementById('emailModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('emailForm').reset();
    document.getElementById('previewEmail').textContent = '-';
    document.getElementById('sendEmailBtn').disabled = true;
    currentActaId = null;
    
    // Limpiar errores
    document.getElementById('recipientEmail').classList.remove('is-invalid');
    document.getElementById('emailError').textContent = '';
});

// Funciones para vista responsive
function toggleExpand(actaId) {
    const details = document.getElementById(`details-${actaId}`);
    const icon = document.getElementById(`icon-${actaId}`);
    const btn = icon.closest('button');
    
    if (details.classList.contains('expanded')) {
        details.classList.remove('expanded');
        icon.style.transform = 'rotate(0deg)';
        btn.innerHTML = '<i class="fas fa-chevron-down"></i> Más detalles';
    } else {
        details.classList.add('expanded');
        icon.style.transform = 'rotate(180deg)';
        btn.innerHTML = '<i class="fas fa-chevron-up"></i> Menos detalles';
    }
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    // Asegurar que la columna Registro esté oculta al inicio
    initializeRegistroColumn();
    
    // Detectar desbordamiento automáticamente
    checkTableOverflow();
    
    // Revisar en resize
    window.addEventListener('resize', checkTableOverflow);
});

// Función para inicializar la columna Registro como oculta
function initializeRegistroColumn() {
    const header = document.getElementById('registro-header');
    const cells = document.querySelectorAll('.registro-cell');
    
    // Asegurar que estén ocultos
    if (header) {
        header.style.display = 'none';
    }
    
    cells.forEach(cell => {
        cell.style.display = 'none';
    });
    
    // Asegurar que el icono esté en estado correcto
    const icon = document.getElementById('icon-registro');
    if (icon) {
        icon.className = 'fas fa-eye-slash';
    }
}

// Función para detectar si la tabla se desborda
function checkTableOverflow() {
    const tableContainer = document.getElementById('tableView');
    const table = document.getElementById('actasTable');
    
    if (tableContainer && table) {
        const container = tableContainer.parentElement;
        const containerWidth = container.offsetWidth;
        const tableWidth = table.scrollWidth;
        
        // Si la tabla es más ancha que el contenedor, aplicar medidas
        if (tableWidth > containerWidth) {
            // Agregar clase para overflow
            tableContainer.classList.add('table-overflow');
            
            // Mostrar indicador de scroll si no existe
            if (!document.getElementById('scroll-indicator')) {
                const indicator = document.createElement('div');
                indicator.id = 'scroll-indicator';
                indicator.className = 'alert alert-info mt-2 mb-0 text-center';
                indicator.innerHTML = '<i class="fas fa-arrows-alt-h"></i> <small>Desliza horizontalmente para ver más información</small>';
                tableContainer.parentElement.insertBefore(indicator, tableContainer.nextSibling);
            }
        } else {
            tableContainer.classList.remove('table-overflow');
            const indicator = document.getElementById('scroll-indicator');
            if (indicator) {
                indicator.remove();
            }
        }
    }
}

// Función para toggle manual de columnas
function toggleColumn(className) {
    const elements = document.querySelectorAll('.' + className);
    const isHidden = elements[0] && elements[0].style.display === 'none';
    
    elements.forEach(element => {
        element.style.display = isHidden ? '' : 'none';
    });
    
    // Actualizar icono
    const iconId = 'icon-' + className.split('-')[2];
    const icon = document.getElementById(iconId);
    if (icon) {
        icon.className = isHidden ? 'fas fa-eye' : 'fas fa-eye-slash';
    }
    
    // Revisar overflow después del cambio
    setTimeout(checkTableOverflow, 100);
}

// Función para toggle de columna específica por índice (para Registro)
function toggleColumnByIndex(columnIndex) {
    const header = document.getElementById('registro-header');
    const cells = document.querySelectorAll('.registro-cell');
    
    const isHidden = header && header.style.display === 'none';
    
    // Toggle header
    if (header) {
        header.style.display = isHidden ? '' : 'none';
    }
    
    // Toggle all cells with registro-cell class
    cells.forEach(cell => {
        cell.style.display = isHidden ? '' : 'none';
    });
    
    // Actualizar icono
    const icon = document.getElementById('icon-registro');
    if (icon) {
        icon.className = isHidden ? 'fas fa-eye' : 'fas fa-eye-slash';
    }
    
    // Revisar overflow después del cambio
    setTimeout(checkTableOverflow, 100);
}
</script>

<!-- Incluir modal de crear acta -->
@include('actas.create')

@endsection




