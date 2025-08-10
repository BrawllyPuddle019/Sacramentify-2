@extends('layouts.app')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">
<style>
    /* Estilos mejorados para la leyenda de colores */
    .color-box {
        width: 24px;
        height: 24px;
        border-radius: 8px;
        display: inline-block;
        border: 2px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
    }

    .color-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s ease;
    }

    .color-box:hover {
        transform: scale(1.2) rotate(8deg);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
        z-index: 10;
    }

    .color-box:hover::before {
        left: 100%;
    }

    /* Mejoras para los controles de formulario optimizadas */
    .form-select,
    .form-control {
        border: 2px solid #e8ecef;
        border-radius: 12px;
        padding: 12px 16px;
        font-weight: 500;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        will-change: transform; /* Optimización para GPU */
    }

    .form-select:focus,
    .form-control:focus {
        border-color: #667eea !important;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.15), 0 4px 12px rgba(102, 126, 234, 0.1) !important;
        transform: translateY(-1px); /* Reducido de -2px a -1px */
        background: #ffffff;
    }

    .form-floating > label {
        color: #6c757d;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-floating > .form-select:focus ~ label,
    .form-floating > .form-select:not(:placeholder-shown) ~ label {
        color: #667eea !important;
        font-weight: 700;
        transform: scale(0.95);
    }

    /* Nuevos estilos para filtros */
    .filter-group {
        position: relative;
        margin-bottom: 1rem;
    }

    .filter-label {
        display: block;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-select {
        background: linear-gradient(135deg, #ffffff, #f8f9fa);
        border: 2px solid #e8ecef;
        border-radius: 10px;
        padding: 12px 16px;
        font-weight: 500;
        font-size: 0.95rem;
        color: #495057;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        width: 100%;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
        will-change: transform;
    }

    .filter-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15), 0 4px 12px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px); /* Reducido de -2px a -1px */
        background: #ffffff;
        outline: none;
    }

    .filter-select:hover {
        border-color: #667eea;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Reducido el shadow */
        /* Removido el transform en hover para mejor rendimiento */
    }

    /* Responsive mejorado para el calendario */
    @media (max-width: 1400px) {
        #calendar {
            padding: 1.5rem;
        }
    }

    @media (max-width: 1200px) {
        #calendar {
            padding: 1rem;
            margin-right: 0;
            margin-left: 0;
        }
        
        .fc-header-toolbar {
            padding: 1rem 1.5rem;
        }
        
        .fc-toolbar-title {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 992px) {
        .filter-group {
            margin-bottom: 1.5rem;
        }
        
        #calendar {
            padding: 0.75rem;
            border-radius: 15px;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
    }

    @media (max-width: 768px) {
        .filter-group {
            margin-bottom: 1rem;
        }
        
        .filter-label {
            font-size: 0.85rem;
        }
        
        .filter-select {
            padding: 10px 14px;
            font-size: 0.9rem;
            background-size: 14px;
            padding-right: 35px;
        }
        
        #calendar {
            padding: 0.5rem;
            border-radius: 12px;
            margin: 0 -0.5rem;
        }
        
        .card-body {
            padding: 1rem !important;
        }
        
        .fc-header-toolbar {
            padding: 0.75rem 1rem;
            margin-bottom: 1rem !important;
        }
        
        .fc-toolbar-title {
            font-size: 1.4rem;
        }
    }

    /* Contenedor del calendario con tamaño fijo */
    .calendar-container {
        width: 100%;
        max-width: 1200px;
        box-sizing: border-box;
        margin: 0 auto;
        transition: all 0.3s ease;
    }

    /* Calendario con dimensiones consistentes */
    .fc-scrollgrid {
        width: 100% !important;
        min-width: 800px;
        table-layout: fixed;
    }

    .fc-scrollgrid-section > * {
        width: 100% !important;
    }

    .fc-scrollgrid-sync-table {
        width: 100% !important;
        table-layout: fixed;
    }

    .fc-daygrid-body {
        width: 100% !important;
    }

    /* Celdas de días con ancho uniforme */
    .fc-daygrid-day {
        width: 14.28571% !important; /* 100% / 7 días */
        min-width: 0 !important;
        box-sizing: border-box;
    }

    .fc-col-header-cell {
        width: 14.28571% !important; /* 100% / 7 días */
        box-sizing: border-box;
    }

    /* Responsive ajustado */
    @media (max-width: 1200px) {
        .calendar-container {
            max-width: 100%;
            overflow-x: auto;
            padding: 0 1rem;
        }
        
        .fc-scrollgrid {
            min-width: 700px;
        }
    }

    @media (max-width: 768px) {
        .calendar-container {
            padding: 0 0.5rem;
        }
        
        .fc-scrollgrid {
            min-width: 600px;
        }
        
        .fc-daygrid-day {
            min-height: 80px;
        }
    }
    
    /* Contenedor principal del calendario mejorado */
    #calendar {
        border: 2px solid #e8ecef;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        overflow: hidden;
        padding: 2rem;
        position: relative;
        transition: all 0.4s ease;
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    #calendar::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        /* Removida la animación shimmer para mejor rendimiento */
    }

    #calendar:hover {
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
        transform: translateY(-2px); /* Reducido de -5px a -2px */
        border-color: #667eea;
    }
    
    /* Estilo general de FullCalendar */
    .fc {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    /* Header del calendario optimizado */
    .fc-header-toolbar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem !important;
        box-shadow: 0 8px 30px rgba(102, 126, 234, 0.4);
        border: 2px solid rgba(255, 255, 255, 0.2);
        position: relative;
        /* Removida la animación deslizante para mejor rendimiento */
    }

    /* Título del calendario optimizado */
    .fc-toolbar-title {
        color: #ffffff !important;
        font-weight: 900;
        font-size: 2.2rem;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4);
        letter-spacing: 1px;
        text-transform: uppercase;
        position: relative;
        z-index: 1;
    }
    
    /* Botones del calendario */
    .fc-button-primary {
        background: rgba(255, 255, 255, 0.2) !important;
        border: 2px solid rgba(255, 255, 255, 0.4) !important;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 8px;
        padding: 8px 16px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    
    .fc-button-primary:hover {
        background: rgba(255, 255, 255, 0.3) !important;
        border-color: rgba(255, 255, 255, 0.6) !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    
    .fc-button-primary:focus {
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3) !important;
    }
    
    .fc-today-button {
        background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
        border: 2px solid #27ae60 !important;
        color: #ffffff !important;
    }
    
    .fc-today-button:hover {
        background: linear-gradient(135deg, #219a52, #27ae60) !important;
        border-color: #219a52 !important;
    }
    
    /* Grilla del calendario */
    .fc-scrollgrid {
        border: 2px solid #e8ecef !important;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    /* Headers de días de la semana */
    .fc-col-header-cell {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
        border: 1px solid #dee2e6 !important;
        border-bottom: 2px solid #ced4da !important;
        padding: 12px 8px;
        font-weight: 700;
        color: #495057;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
    }
    
    .fc-col-header-cell:hover {
        background: linear-gradient(135deg, #e9ecef, #dee2e6) !important;
    }
    
    /* Celdas de días optimizadas */
    .fc-daygrid-day {
        border: 1px solid #e8ecef !important;
        background: #ffffff;
        transition: background-color 0.2s ease;
        position: relative;
    }

    .fc-daygrid-day:hover {
        background: #f8f9fa !important;
        /* Removida la sombra interna para mejor rendimiento */
    }

    /* Números de días optimizados */
    .fc-daygrid-day-number {
        color: #495057;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 8px 10px;
        border-radius: 6px;
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .fc-daygrid-day-number:hover {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
    }    /* Día actual */
    .fc-day-today {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1)) !important;
        border: 2px solid #667eea !important;
        box-shadow: 0 0 15px rgba(102, 126, 234, 0.3);
    }
    
    .fc-day-today .fc-daygrid-day-number {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #ffffff !important;
        border-radius: 8px;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
    }
    
    /* Días de otros meses */
    .fc-day-other {
        background: #fafbfc !important;
        opacity: 0.6;
    }
    
    .fc-day-other .fc-daygrid-day-number {
        color: #adb5bd !important;
    }
    
    /* Eventos optimizados */
    .fc-event {
        border: 2px solid rgba(255, 255, 255, 0.3) !important;
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        will-change: transform;
    }

    .fc-event:hover {
        transform: translateY(-1px); /* Reducido de -2px a -1px */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        z-index: 10;
    }    .fc-daygrid-event {
        border-radius: 8px;
        padding: 4px 8px;
        margin: 2px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    
    .fc-event-title {
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }
    
    /* Colores mejorados para eventos */
    .fc-event[style*="background-color: rgb(39, 174, 96)"] {
        background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
        border-color: #1e8449 !important;
    }
    
    .fc-event[style*="background-color: rgb(52, 152, 219)"] {
        background: linear-gradient(135deg, #3498db, #5dade2) !important;
        border-color: #2980b9 !important;
    }
    
    .fc-event[style*="background-color: rgb(243, 156, 18)"] {
        background: linear-gradient(135deg, #f39c12, #f5b041) !important;
        border-color: #d68910 !important;
    }
    
    .fc-event[style*="background-color: rgb(231, 76, 60)"] {
        background: linear-gradient(135deg, #e74c3c, #ec7063) !important;
        border-color: #c0392b !important;
    }
    
    .fc-event[style*="background-color: rgb(149, 165, 166)"] {
        background: linear-gradient(135deg, #95a5a6, #b2babb) !important;
        border-color: #7f8c8d !important;
    }
    
    /* Líneas de cuadrícula más definidas */
    .fc-scrollgrid-section > * {
        border-color: #dee2e6 !important;
    }
    
    /* Mejorar la vista en dispositivos móviles */
    @media (max-width: 768px) {
        #calendar {
            padding: 10px;
            border-radius: 8px;
        }
        
        .fc-header-toolbar {
            padding: 10px 15px;
            flex-direction: column;
            gap: 10px;
        }
        
        .fc-toolbar-title {
            font-size: 1.4rem;
        }
        
        .fc-button-primary {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
        
        .fc-col-header-cell {
            padding: 8px 4px;
            font-size: 0.75rem;
        }
        
        .fc-daygrid-day-number {
            font-size: 0.85rem;
            padding: 6px 8px;
        }
    }
    
    /* Efectos adicionales optimizados */
    .fc-scrollgrid-section-header {
        background: linear-gradient(to bottom, #ffffff, #f8f9fa) !important;
    }

    .fc-scroller {
        border-radius: 0 0 8px 8px;
    }

    /* Animaciones eliminadas para mejor rendimiento */
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card" style="border: none; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border-radius: 15px; overflow: hidden;">
            <div class="card-body" style="padding: 2rem;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; font-size: 2.2rem;">
                            📅 Calendario de Eventos
                        </h2>
                        <p class="text-muted mb-0" style="font-size: 1.1rem; margin-top: 0.5rem;">Gestiona y visualiza todos los eventos de la parroquia</p>
                    </div>
                    @if(auth()->user()->is_admin)
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal" 
                            style="background: linear-gradient(135deg, #667eea, #764ba2); border: none; border-radius: 10px; padding: 12px 24px; font-weight: 600; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); transition: all 0.3s ease;">
                        <i class="fas fa-plus me-2"></i> Nuevo Evento
                    </button>
                    @endif
                </div>

                <!-- Filtros mejorados -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-filter me-2"></i>Tipo de Evento
                            </label>
                            <select class="form-select filter-select" id="filtroTipo">
                                <option value="todos">Todos los tipos</option>
                                <option value="platica">Pláticas</option>
                                <option value="bautizo">Bautizos</option>
                                <option value="confirmacion">Confirmaciones</option>
                                <option value="matrimonio">Matrimonios</option>
                                <option value="otro">Otros</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-check-circle me-2"></i>Estado
                            </label>
                            <select class="form-select filter-select" id="filtroEstado">
                                <option value="todos">Todos los estados</option>
                                <option value="pendiente">Pendientes</option>
                                <option value="confirmado">Confirmados</option>
                                <option value="cancelado">Cancelados</option>
                                <option value="completado">Completados</option>
                            </select>
                        </div>
                    </div>
                    @if(!auth()->user()->is_admin)
                    <div class="col-md-6">
                        <div class="alert alert-info mb-0" style="border-radius: 10px; border: 2px solid #d1ecf1; background: linear-gradient(135deg, rgba(52, 152, 219, 0.1), rgba(52, 152, 219, 0.05));">
                            <i class="fas fa-info-circle me-2"></i> 
                            <strong>Nota:</strong> Solo puedes ver tus propios eventos. Los eventos creados quedarán pendientes hasta que un administrador los confirme.
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Leyenda de colores mejorada -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef); border: 2px solid #dee2e6; border-radius: 12px; padding: 1rem;">
                            <h6 class="mb-3" style="color: #495057; font-weight: 700;">
                                <i class="fas fa-palette me-2"></i>Leyenda de Eventos
                            </h6>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="d-flex align-items-center">
                                    <div class="color-box" style="background: linear-gradient(135deg, #27ae60, #2ecc71);"></div>
                                    <span class="ms-2 fw-semibold">Pláticas</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="color-box" style="background: linear-gradient(135deg, #3498db, #5dade2);"></div>
                                    <span class="ms-2 fw-semibold">Bautizos</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="color-box" style="background: linear-gradient(135deg, #f39c12, #f5b041);"></div>
                                    <span class="ms-2 fw-semibold">Confirmaciones</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="color-box" style="background: linear-gradient(135deg, #e74c3c, #ec7063);"></div>
                                    <span class="ms-2 fw-semibold">Matrimonios</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="color-box" style="background: linear-gradient(135deg, #95a5a6, #b2babb);"></div>
                                    <span class="ms-2 fw-semibold">Otros</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendario -->
                <div id="calendar" class="calendar-container"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para crear evento -->
@if(auth()->user()->is_admin)
<div class="modal fade" id="createEventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nuevo Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createEventForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo *</label>
                                <select class="form-select" id="tipo" name="tipo" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="platica">Plática</option>
                                    <option value="bautizo">Bautizo</option>
                                    <option value="confirmacion">Confirmación</option>
                                    <option value="matrimonio">Matrimonio</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha y Hora de Inicio *</label>
                                <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fecha_fin" class="form-label">Fecha y Hora de Fin *</label>
                                <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="todo_el_dia" name="todo_el_dia">
                            <label class="form-check-label" for="todo_el_dia">
                                Todo el día
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="sacerdote_id" class="form-label">Sacerdote</label>
                                <select class="form-select" id="sacerdote_id" name="sacerdote_id">
                                    <option value="">Sin asignar</option>
                                    @foreach($sacerdotes as $sacerdote)
                                    <option value="{{ $sacerdote->cve_sacerdotes }}">
                                        {{ $sacerdote->nombre_sacerdote }} {{ $sacerdote->apellido_paterno }} {{ $sacerdote->apellido_materno }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="ermita_id" class="form-label">Ermita</label>
                                <select class="form-select" id="ermita_id" name="ermita_id">
                                    <option value="">Sin asignar</option>
                                    @foreach($ermitas as $ermita)
                                    <option value="{{ $ermita->cve_ermitas }}">{{ $ermita->nombre_ermita }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contacto_email" class="form-label">Email de Contacto</label>
                                <input type="email" class="form-control" id="contacto_email" name="contacto_email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contacto_telefono" class="form-label">Teléfono de Contacto</label>
                                <input type="tel" class="form-control" id="contacto_telefono" name="contacto_telefono">
                            </div>
                        </div>
                    </div>

                    <!-- Campos específicos por tipo de evento -->
                    <div id="personasSection" style="display: none;">
                        <hr>
                        <h6 class="mb-3">Personas Involucradas</h6>
                        
                        <!-- Para Pláticas -->
                        <div id="platicaPersonas" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="padre_id" class="form-label">Padre</label>
                                        <select class="form-select" id="padre_id" name="padre_id">
                                            <option value="">Seleccionar padre</option>
                                            @foreach($personas->where('sexo', 1) as $persona)
                                            <option value="{{ $persona->cve_persona }}">
                                                {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="madre_id" class="form-label">Madre</label>
                                        <select class="form-select" id="madre_id" name="madre_id">
                                            <option value="">Seleccionar madre</option>
                                            @foreach($personas->where('sexo', 0) as $persona)
                                            <option value="{{ $persona->cve_persona }}">
                                                {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Para Bautizos, Confirmaciones -->
                        <div id="sacramentoPersonas" style="display: none;">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="persona_principal_id" class="form-label">Persona Principal</label>
                                        <select class="form-select" id="persona_principal_id" name="persona_principal_id">
                                            <option value="">Seleccionar persona</option>
                                            @foreach($personas as $persona)
                                            <option value="{{ $persona->cve_persona }}">
                                                {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="padrino_id" class="form-label">Padrino</label>
                                        <select class="form-select" id="padrino_id" name="padrino_id">
                                            <option value="">Seleccionar padrino</option>
                                            @foreach($personas->where('sexo', 1) as $persona)
                                            <option value="{{ $persona->cve_persona }}">
                                                {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="madrina_id" class="form-label">Madrina</label>
                                        <select class="form-select" id="madrina_id" name="madrina_id">
                                            <option value="">Seleccionar madrina</option>
                                            @foreach($personas->where('sexo', 0) as $persona)
                                            <option value="{{ $persona->cve_persona }}">
                                                {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Para Matrimonios -->
                        <div id="matrimonioPersonas" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="novio_id" class="form-label">Novio</label>
                                        <select class="form-select" id="novio_id" name="padre_id">
                                            <option value="">Seleccionar novio</option>
                                            @foreach($personas->where('sexo', 1) as $persona)
                                            <option value="{{ $persona->cve_persona }}">
                                                {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="novia_id" class="form-label">Novia</label>
                                        <select class="form-select" id="novia_id" name="madre_id">
                                            <option value="">Seleccionar novia</option>
                                            @foreach($personas->where('sexo', 0) as $persona)
                                            <option value="{{ $persona->cve_persona }}">
                                                {{ $persona->nombre }} {{ $persona->paterno }} {{ $persona->materno }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="notas" class="form-label">Notas</label>
                        <textarea class="form-control" id="notas" name="notas" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Evento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Modal para ver/editar evento -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalTitle">Detalles del Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="eventModalBody">
                <!-- Contenido dinámico -->
            </div>
            <div class="modal-footer" id="eventModalFooter">
                <!-- Botones dinámicos -->
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/locales/es.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        let calendar;

        // Inicializar calendario
        function initCalendar() {
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hoy',
                    month: 'Mes',
                    week: 'Semana',
                    day: 'Día'
                },
                height: 'auto',
                events: function(info, successCallback, failureCallback) {
                    const filtroTipo = document.getElementById('filtroTipo').value;
                    const filtroEstado = document.getElementById('filtroEstado').value;
                    
                    fetch(`{{ route('calendario.eventos') }}?start=${info.startStr}&end=${info.endStr}&tipo=${filtroTipo}&estado=${filtroEstado}`)
                        .then(response => response.json())
                        .then(data => successCallback(data))
                        .catch(error => {
                            console.error('Error loading events:', error);
                            failureCallback(error);
                        });
                },
                eventClick: function(info) {
                    showEventDetails(info.event.id);
                },
                @if(auth()->user()->is_admin)
                selectable: true,
                select: function(info) {
                    openCreateModal(info.startStr, info.endStr);
                },
                @endif
                eventDisplay: 'block',
                dayMaxEvents: 3,
                moreLinkClick: 'popover'
            });

            calendar.render();
        }

        // Mostrar detalles del evento
        function showEventDetails(eventId) {
            fetch(`{{ url('calendario/eventos') }}/${eventId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayEventModal(data.evento);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'No se pudieron cargar los detalles del evento', 'error');
                });
        }

        // Mostrar modal de evento
        function displayEventModal(evento) {
            const modalTitle = document.getElementById('eventModalTitle');
            const modalBody = document.getElementById('eventModalBody');
            const modalFooter = document.getElementById('eventModalFooter');

            modalTitle.textContent = evento.titulo;

            // Formatear fechas de manera segura
            let fechaInicio = 'No definida';
            let fechaFin = 'No definida';
            
            if (evento.start) {
                const startDate = new Date(evento.start);
                fechaInicio = !isNaN(startDate) ? startDate.toLocaleString('es-ES') : 'Fecha no válida';
            }
            
            if (evento.end) {
                const endDate = new Date(evento.end);
                fechaFin = !isNaN(endDate) ? endDate.toLocaleString('es-ES') : 'Fecha no válida';
            }

            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tipo:</strong> <span class="badge bg-primary">${evento.extendedProps.tipo}</span></p>
                        <p><strong>Estado:</strong> <span class="badge bg-${getStatusBadgeColor(evento.extendedProps.estado)}">${evento.extendedProps.estado}</span></p>
                        <p><strong>Fecha Inicio:</strong> ${fechaInicio}</p>
                        <p><strong>Fecha Fin:</strong> ${fechaFin}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Sacerdote:</strong> ${evento.extendedProps.sacerdote || 'Sin asignar'}</p>
                        <p><strong>Ermita:</strong> ${evento.extendedProps.ermita || 'Sin asignar'}</p>
                        <p><strong>Creado por:</strong> ${evento.user ? evento.user.name : 'Sistema'}</p>
                    </div>
                </div>
                ${evento.extendedProps.descripcion ? `<p><strong>Descripción:</strong><br>${evento.extendedProps.descripcion}</p>` : ''}
                ${evento.extendedProps.contacto_email ? `<p><strong>Email:</strong> ${evento.extendedProps.contacto_email}</p>` : ''}
                ${evento.extendedProps.contacto_telefono ? `<p><strong>Teléfono:</strong> ${evento.extendedProps.contacto_telefono}</p>` : ''}
                ${evento.extendedProps.notas ? `<p><strong>Notas:</strong><br>${evento.extendedProps.notas}</p>` : ''}
            `;

            modalFooter.innerHTML = `
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                @if(auth()->user()->is_admin)
                ${evento.estado === 'pendiente' ? `<button type="button" class="btn btn-success" onclick="cambiarEstado(${evento.id}, 'confirmado')">Confirmar</button>` : ''}
                ${evento.extendedProps.estado === 'confirmado' ? `<button type="button" class="btn btn-info" onclick="cambiarEstado(${evento.id}, 'completado')">Marcar como Completado</button>` : ''}
                ${evento.extendedProps.estado !== 'cancelado' && evento.extendedProps.estado !== 'completado' ? `<button type="button" class="btn btn-warning" onclick="cambiarEstado(${evento.id}, 'cancelado')">Cancelar</button>` : ''}
                <button type="button" class="btn btn-danger" onclick="eliminarEvento(${evento.id})">Eliminar</button>
                @endif
            `;

            new bootstrap.Modal(document.getElementById('eventModal')).show();
        }

        // Obtener color del badge según el estado
        function getStatusBadgeColor(estado) {
            const colors = {
                'pendiente': 'warning',
                'confirmado': 'success',
                'cancelado': 'danger',
                'completado': 'info'
            };
            return colors[estado] || 'secondary';
        }

        @if(auth()->user()->is_admin)
        // Abrir modal de creación
        function openCreateModal(start, end) {
            document.getElementById('fecha_inicio').value = start;
            document.getElementById('fecha_fin').value = end;
            new bootstrap.Modal(document.getElementById('createEventModal')).show();
        }

        // Crear evento
        document.getElementById('createEventForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route('calendario.store') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('createEventModal')).hide();
                    calendar.refetchEvents();
                    this.reset();
                } else {
                    if (data.errors) {
                        let errorMsg = '';
                        Object.values(data.errors).forEach(errors => {
                            errors.forEach(error => errorMsg += error + '\n');
                        });
                        Swal.fire('Error de Validación', errorMsg, 'error');
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo crear el evento', 'error');
            });
        });

        // Cambiar estado del evento
        window.cambiarEstado = function(eventoId, nuevoEstado) {
            fetch(`{{ url('calendario/eventos') }}/${eventoId}/estado`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ estado: nuevoEstado })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Éxito', data.message, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                    calendar.refetchEvents();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'No se pudo cambiar el estado del evento', 'error');
            });
        };

        // Eliminar evento
        window.eliminarEvento = function(eventoId) {
            Swal.fire({
                title: '¿Está seguro?',
                text: 'Esta acción no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#95a5a6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('calendario/eventos') }}/${eventoId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Eliminado', data.message, 'success');
                            bootstrap.Modal.getInstance(document.getElementById('eventModal')).hide();
                            calendar.refetchEvents();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'No se pudo eliminar el evento', 'error');
                    });
                }
            });
        };
        @endif

        // Manejar visibilidad de campos de personas según tipo de evento
        document.getElementById('tipo').addEventListener('change', function() {
            const tipo = this.value;
            const personasSection = document.getElementById('personasSection');
            const platicaPersonas = document.getElementById('platicaPersonas');
            const sacramentoPersonas = document.getElementById('sacramentoPersonas');
            const matrimonioPersonas = document.getElementById('matrimonioPersonas');

            // Ocultar todas las secciones
            platicaPersonas.style.display = 'none';
            sacramentoPersonas.style.display = 'none';
            matrimonioPersonas.style.display = 'none';

            // Mostrar la sección correspondiente
            if (tipo === 'platica') {
                personasSection.style.display = 'block';
                platicaPersonas.style.display = 'block';
            } else if (tipo === 'bautizo' || tipo === 'confirmacion') {
                personasSection.style.display = 'block';
                sacramentoPersonas.style.display = 'block';
            } else if (tipo === 'matrimonio') {
                personasSection.style.display = 'block';
                matrimonioPersonas.style.display = 'block';
            } else {
                personasSection.style.display = 'none';
            }
        });

        // Limpiar formulario al cerrar modal
        document.getElementById('createEventModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('createEventForm').reset();
            document.getElementById('personasSection').style.display = 'none';
        });

        // Filtros
        document.getElementById('filtroTipo').addEventListener('change', function() {
            calendar.refetchEvents();
        });

        document.getElementById('filtroEstado').addEventListener('change', function() {
            calendar.refetchEvents();
        });

        // Inicializar calendario
        initCalendar();
    });
</script>
@endpush
