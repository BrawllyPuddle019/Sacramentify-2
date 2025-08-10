<!-- Modal para gestionar ubicaciones de ermitas -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">
                    <i class="fas fa-map-marked-alt text-info"></i> Gestionar Ubicación de Ermita
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="locationForm">
                <div class="modal-body">
                    <div class="row">
                        <!-- Información de la ermita -->
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-church"></i> Información de la Ermita</h6>
                                <div id="ermitaInfo">
                                    <strong>Nombre:</strong> <span id="ermitaNombre">-</span><br>
                                    <strong>Parroquia:</strong> <span id="ermitaParroquia">-</span><br>
                                    <strong>Municipio:</strong> <span id="ermitaMunicipio">-</span>
                                </div>
                            </div>

                            <!-- Formulario de ubicación -->
                            <div class="mb-3">
                                <label for="addressSearch" class="form-label">
                                    <i class="fas fa-search"></i> Buscar Dirección
                                </label>
                                <input type="text" class="form-control" id="addressSearch" 
                                       placeholder="Escribe la dirección de la ermita...">
                                <div class="form-text">Ejemplo: Iglesia San Juan, Guadalajara, Jalisco</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="latitud" class="form-label">Latitud</label>
                                        <input type="number" class="form-control" id="latitud" 
                                               step="0.00000001" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="longitud" class="form-label">Longitud</label>
                                        <input type="number" class="form-control" id="longitud" 
                                               step="0.00000001" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="direccionCompleta" class="form-label">Dirección Completa</label>
                                <textarea class="form-control" id="direccionCompleta" rows="3" readonly></textarea>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-success" id="getCurrentLocation">
                                    <i class="fas fa-crosshairs"></i> Usar Mi Ubicación Actual
                                </button>
                                <button type="button" class="btn btn-warning" id="clearLocation">
                                    <i class="fas fa-eraser"></i> Limpiar Ubicación
                                </button>
                            </div>
                        </div>

                        <!-- Mapa -->
                        <div class="col-md-8">
                            <div class="mb-2">
                                <strong><i class="fas fa-map"></i> Selecciona la ubicación en el mapa:</strong>
                                <small class="text-muted">(Haz clic en el mapa para marcar la ubicación exacta)</small>
                            </div>
                            <div id="map" style="height: 500px; border: 2px solid #dee2e6; border-radius: 8px;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="saveLocationBtn" disabled>
                        <i class="fas fa-save"></i> Guardar Ubicación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
#locationModal .alert-info {
    background-color: #e8f4fd;
    border-color: #bee5eb;
    color: #0c5460;
}

#map {
    background-color: #f8f9fa;
}

.gm-style-iw {
    text-align: center;
}
</style>

<script>
let map;
let marker;
let geocoder;
let currentErmitaId = null;

function initMap() {
    // Configuración inicial del mapa (centrado en Guadalajara)
    const defaultLocation = { lat: 20.6597, lng: -103.3496 };
    
    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: defaultLocation,
        mapTypeControl: true,
        streetViewControl: true,
        fullscreenControl: true,
    });

    // Inicializar geocoder
    geocoder = new google.maps.Geocoder();

    // Event listener para click en el mapa
    map.addListener("click", (event) => {
        setMarker(event.latLng);
    });

    // Autocomplete para el campo de búsqueda
    const autocomplete = new google.maps.places.Autocomplete(
        document.getElementById("addressSearch"),
        {
            types: ["establishment", "geocode"],
            componentRestrictions: { country: "mx" },
        }
    );

    autocomplete.addListener("place_changed", () => {
        const place = autocomplete.getPlace();
        if (place.geometry) {
            setMarker(place.geometry.location);
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
    });
}

function setMarker(location) {
    // Remover marcador anterior
    if (marker) {
        marker.setMap(null);
    }

    // Crear nuevo marcador
    marker = new google.maps.Marker({
        position: location,
        map: map,
        title: "Ubicación de la Ermita",
        animation: google.maps.Animation.DROP,
    });

    // Actualizar campos del formulario
    document.getElementById("latitud").value = location.lat().toFixed(8);
    document.getElementById("longitud").value = location.lng().toFixed(8);
    
    // Geocodificación inversa para obtener dirección
    geocoder.geocode({ location: location }, (results, status) => {
        if (status === "OK" && results[0]) {
            document.getElementById("direccionCompleta").value = results[0].formatted_address;
        }
    });

    // Habilitar botón de guardar
    document.getElementById("saveLocationBtn").disabled = false;
}

function openLocationModal(ermitaId) {
    currentErmitaId = ermitaId;
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('locationModal'));
    modal.show();
    
    // Cargar información de la ermita
    // Aquí puedes hacer una petición AJAX para obtener los datos de la ermita
    // Por ahora uso datos de ejemplo
    document.getElementById('ermitaNombre').textContent = 'Cargando...';
    document.getElementById('ermitaParroquia').textContent = 'Cargando...';
    document.getElementById('ermitaMunicipio').textContent = 'Cargando...';
    
    // Limpiar formulario
    clearLocationForm();
    
    // Reinicializar mapa después de que el modal se muestre
    setTimeout(() => {
        google.maps.event.trigger(map, 'resize');
        map.setCenter({ lat: 20.6597, lng: -103.3496 });
    }, 500);
}

function clearLocationForm() {
    document.getElementById("addressSearch").value = '';
    document.getElementById("latitud").value = '';
    document.getElementById("longitud").value = '';
    document.getElementById("direccionCompleta").value = '';
    document.getElementById("saveLocationBtn").disabled = true;
    
    if (marker) {
        marker.setMap(null);
        marker = null;
    }
}

// Event listeners
document.getElementById("getCurrentLocation").addEventListener("click", () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const location = new google.maps.LatLng(
                    position.coords.latitude,
                    position.coords.longitude
                );
                setMarker(location);
                map.setCenter(location);
                map.setZoom(17);
            },
            () => {
                alert("Error: No se pudo obtener tu ubicación actual.");
            }
        );
    } else {
        alert("Error: Tu navegador no soporta geolocalización.");
    }
});

document.getElementById("clearLocation").addEventListener("click", clearLocationForm);

document.getElementById("locationForm").addEventListener("submit", (e) => {
    e.preventDefault();
    
    const latitud = document.getElementById("latitud").value;
    const longitud = document.getElementById("longitud").value;
    const direccion = document.getElementById("direccionCompleta").value;
    
    if (!latitud || !longitud) {
        alert("Por favor, selecciona una ubicación en el mapa.");
        return;
    }
    
    // Aquí harías la petición AJAX para guardar la ubicación
    console.log("Guardar ubicación:", {
        ermita_id: currentErmitaId,
        latitud: latitud,
        longitud: longitud,
        direccion_completa: direccion
    });
    
    // Simular guardado exitoso
    alert("Ubicación guardada exitosamente!");
    bootstrap.Modal.getInstance(document.getElementById('locationModal')).hide();
});

// Inicializar mapa cuando se cargue la página
window.onload = function() {
    // El mapa se inicializará cuando se cargue la API de Google Maps
};
</script>
