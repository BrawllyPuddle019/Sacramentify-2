@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/responsive-tables.css') }}">

<div class="card animate__animated animate__fadeIn">
    <div class="card-body">
        <h2 class="mb-0 text-center">Tabla de Ermitas</h2>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <button style="background-color: #1061d2; color: #ffffff;" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createErmita">
                AGREGAR
            </button>
        </div>

        <div class="table-responsive table-container animate__animated animate__fadeIn" style="max-height: 800px; overflow-y: auto;">
            <table class="table table-bordered table-hover align-middle table-compact">
                <thead class="table-dark text-white">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Parroquia</th>
                        <th scope="col">Municipio</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Ubicación</th>
                        <th class="actions-column header">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ermitas as $ermita)
                    <tr>
                        <td data-label="ID">{{$ermita->cve_ermitas}}</td>
                        <td>{{$ermita->parroquia ? $ermita->parroquia->nombre_parroquia : 'Sin parroquia'}}</td>
                        <td>{{$ermita->municipio ? $ermita->municipio->nombre_municipio : 'Sin municipio'}}</td>
                        <td>{{$ermita->nombre_ermita}}</td>
                        <td data-label="Ubicación">
                            @if($ermita->latitude && $ermita->longitude)
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{$ermita->latitude}},{{$ermita->longitude}}" 
                                   target="_blank" class="btn btn-info btn-sm" title="Cómo llegar">
                                    <i class="fas fa-map-marker-alt"></i> Cómo llegar
                                </a>
                            @else
                                <span class="text-muted">Sin ubicación</span>
                            @endif
                        </td>
                        <td data-label="Acciones" class="actions-column">
                            <div class="btn-group-compact">
                                <button type="button" class="btn btn-warning btn-sm" 
                                        onclick="openLocationModal({{$ermita->cve_ermitas}})" title="Gestionar ubicación">
                                    <i class="fas fa-map"></i>
                                </button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editErmita{{$ermita->cve_ermitas}}">
                                    Editar
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteErmita{{$ermita->cve_ermitas}}">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @include('ermitas.info')
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('ermitas.create')
        @include('shared.location-modal')
    </div>
</div>

<script>
function openLocationModal(ermitaId) {
    console.log('openLocationModal llamada con ID:', ermitaId);
    
    // Verificar que los elementos existen
    const modal = document.getElementById('locationModal');
    const nombreSpan = document.getElementById('ermitaNombre');
    
    if (!modal) {
        console.error('Modal locationModal no encontrado');
        alert('Error: Modal no encontrado');
        return;
    }
    
    if (!nombreSpan) {
        console.error('Elemento ermitaNombre no encontrado');
        alert('Error: Elemento ermitaNombre no encontrado');
        return;
    }
    
    console.log('Elementos encontrados, abriendo modal...');
    
    // Resetear el modal
    modal.removeAttribute('data-ermita-id');
    nombreSpan.textContent = 'Cargando...';
    
    const direccionEl = document.getElementById('direccionCompleta');
    const latitudEl = document.getElementById('latitud');
    const longitudEl = document.getElementById('longitud');
    const searchEl = document.getElementById('searchAddress');
    const saveBtn = document.getElementById('saveLocationBtn');
    
    if (direccionEl) direccionEl.value = '';
    if (latitudEl) latitudEl.value = '';
    if (longitudEl) longitudEl.value = '';
    if (searchEl) searchEl.value = '';
    if (saveBtn) saveBtn.disabled = true;
    
    // Abrir el modal
    try {
        var locationModal = new bootstrap.Modal(modal);
        locationModal.show();
        console.log('Modal abierto exitosamente');
    } catch (error) {
        console.error('Error al abrir modal:', error);
        alert('Error al abrir modal: ' + error.message);
    }
    
    // Cargar datos de la ermita
    console.log('Cargando datos de ermita...');
    fetch(`/ermitas/${ermitaId}/location`)
        .then(response => {
            console.log('Respuesta recibida:', response);
            return response.json();
        })
        .then(data => {
            console.log('Datos recibidos:', data);
            if (data.success) {
                modal.setAttribute('data-ermita-id', ermitaId);
                nombreSpan.textContent = data.data.nombre;
                if (direccionEl) direccionEl.value = data.data.direccion_completa || '';
                
                // Si ya tiene coordenadas, mostrarlas en el mapa
                if (data.data.latitud && data.data.longitud) {
                    console.log('Actualizando ubicación del mapa...');
                    if (typeof updateMapLocation === 'function') {
                        updateMapLocation(parseFloat(data.data.latitud), parseFloat(data.data.longitud));
                    }
                    if (saveBtn) saveBtn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Error al cargar datos:', error);
            alert('Error al cargar los datos de la ermita: ' + error.message);
        });
}

function saveLocation() {
    const ermitaId = document.getElementById('locationModal').getAttribute('data-ermita-id');
    const latitud = document.getElementById('latitud').value;
    const longitud = document.getElementById('longitud').value;
    const direccion = document.getElementById('direccionCompleta').value;
    
    if (!latitud || !longitud) {
        alert('Por favor, selecciona una ubicación en el mapa');
        return;
    }
    
    if (!direccion.trim()) {
        alert('Por favor, ingresa la dirección completa');
        return;
    }
    
    const locationData = {
        latitude: parseFloat(latitud),
        longitude: parseFloat(longitud),
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    document.getElementById('saveLocationBtn').disabled = true;
    document.getElementById('saveLocationBtn').textContent = 'Guardando...';
    
    fetch(`/ermitas/${ermitaId}/location`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': locationData._token
        },
        body: JSON.stringify(locationData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Ubicación guardada exitosamente');
            var locationModal = bootstrap.Modal.getInstance(document.getElementById('locationModal'));
            locationModal.hide();
            // Recargar la página para mostrar los cambios
            location.reload();
        } else {
            alert('Error al guardar la ubicación');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar la ubicación');
    })
    .finally(() => {
        document.getElementById('saveLocationBtn').disabled = false;
        document.getElementById('saveLocationBtn').textContent = 'Guardar Ubicación';
    });
}
</script>

@endsection
