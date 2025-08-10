@extends('layouts.app')

@section('content')
<style>
    .table-responsive {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
        padding: 1rem 0.75rem;
    }
    
    .table td {
        padding: 0.75rem;
        vertical-align: middle;
        border-top: 1px solid #e9ecef;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
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

    .deleted-row {
        background-color: #fff3cd;
        opacity: 0.8;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">🗑️ Papelera de Actas</h2>
                    <a href="{{ route('actas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver a Actas
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th class="text-center" style="width: 100px;">N° Consec.</th>
                                <th class="text-center" style="width: 120px;">Sacramento</th>
                                <th class="text-center" style="width: 200px;">Persona(s)</th>
                                <th class="text-center" style="width: 120px;">Fecha</th>
                                <th class="text-center" style="width: 150px;">Eliminado</th>
                                <th class="text-center" style="width: 150px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($actas as $index => $acta)
                            <tr class="deleted-row">
                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ $actas->firstItem() + $index }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning">{{ $acta->numero_consecutivo ?? 'N/A' }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $tipoActa = $acta->tipoActa ? trim($acta->tipoActa->nombre) : '';
                                    @endphp
                                    <span class="badge 
                                        @if($tipoActa == 'Matrimonio') bg-danger
                                        @elseif($tipoActa == 'Bautizo') bg-primary  
                                        @elseif($tipoActa == 'Confirmación') bg-warning
                                        @else bg-secondary @endif">
                                        {{ $tipoActa ?: 'Sin tipo' }}
                                    </span>
                                </td>
                                <td>
                                    @if($acta->persona)
                                        <strong>{{ $acta->persona->nombre }} {{ $acta->persona->paterno }}</strong>
                                        @if($acta->persona->materno)
                                            {{ $acta->persona->materno }}
                                        @endif
                                    @endif
                                    
                                    @if($acta->persona2)
                                        <br><small class="text-muted">
                                            <strong>y</strong> {{ $acta->persona2->nombre }} {{ $acta->persona2->paterno }}
                                            @if($acta->persona2->materno)
                                                {{ $acta->persona2->materno }}
                                            @endif
                                        </small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($acta->fecha)
                                        <span class="small">{{ \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') }}</span>
                                    @else
                                        <span class="text-muted small">Sin fecha</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="small text-muted">
                                        {{ $acta->deleted_at->format('d/m/Y H:i') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column gap-1">
                                        @if(auth()->user() && auth()->user()->is_admin)
                                            <button class="btn btn-success btn-sm" onclick="confirmarRestaurar({{ $acta->cve_actas }})">
                                                <i class="fas fa-undo"></i> Restaurar
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="confirmarEliminacionPermanente({{ $acta->cve_actas }})">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>No hay actas eliminadas.</strong><br>
                                        <small>La papelera está vacía.</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $actas->links() }}
                </div>
                
                <!-- Contador de registros -->
                <div class="mt-3 text-center">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i> 
                        Mostrando {{ $actas->firstItem() ?? 0 }} - {{ $actas->lastItem() ?? 0 }} de {{ $actas->total() }} acta(s) en la papelera
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formularios ocultos para acciones -->
<form id="restaurarForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<form id="eliminarPermanenteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmarRestaurar(id) {
    Swal.fire({
        title: '¿Restaurar Acta?',
        text: 'Esta acta será restaurada y volverá a aparecer en la lista principal.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, restaurar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('restaurarForm');
            form.action = `/actas/${id}/restore`;
            form.submit();
        }
    });
}

function confirmarEliminacionPermanente(id) {
    Swal.fire({
        title: '⚠️ ¡PELIGRO!',
        text: 'Esta acción eliminará permanentemente el registro. ¡NO SE PUEDE DESHACER!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar permanentemente',
        cancelButtonText: 'Cancelar',
        input: 'text',
        inputPlaceholder: 'Escribe "ELIMINAR" para confirmar',
        inputValidator: (value) => {
            if (value !== 'ELIMINAR') {
                return 'Debes escribir "ELIMINAR" para confirmar';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('eliminarPermanenteForm');
            form.action = `/actas/${id}/force-delete`;
            form.submit();
        }
    });
}
</script>

@endsection
