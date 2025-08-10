<!-- Modal para gestionar ubicaciones de ermitas con Mapbox -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="locationModalLabel">
                    <i class="fas fa-map-marked-alt"></i> Gestionar Ubicación de Ermita
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Información de la ermita -->
                <div class="alert alert-info border-0">
                    <h6><i class="fas fa-church text-primary"></i> Ermita: <span id="ermitaNombre" class="fw-bold">Cargando...</span></h6>
                </div>

                <div class="row">
                    <!-- Mapa interactivo -->
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-map text-success"></i> Selecciona la ubicación en el mapa
                            </label>
                            <div id="map" style="height: 450px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);"></div>
                        </div>
                    </div>

                    <!-- Panel de control -->
                    <div class="col-md-4">
                        <!-- Búsqueda -->
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-primary">
                                    <i class="fas fa-search"></i> Buscar Ubicación
                                </h6>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="searchAddress" 
                                           placeholder="Buscar dirección...">
                                    <button type="button" class="btn btn-primary" id="searchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Información de ubicación -->
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <h6 class="card-title text-success">
                                    <i class="fas fa-map-pin"></i> Información de Ubicación
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="direccionCompleta" class="form-label">Dirección Completa</label>
                                    <textarea class="form-control" id="direccionCompleta" rows="3" 
                                              placeholder="La dirección se completará automáticamente..."></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label for="latitud" class="form-label">Latitud</label>
                                        <input type="number" class="form-control" id="latitud" 
                                               step="0.000001" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label for="longitud" class="form-label">Longitud</label>
                                        <input type="number" class="form-control" id="longitud" 
                                               step="0.000001" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del servicio -->
                        <div class="alert alert-success border-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-rocket fa-2x text-success me-3"></i>
                                <div>
                                    <h6 class="mb-1">Mapbox</h6>
                                    <small>50,000 cargas gratis/mes<br>
                                    Sin tarjeta de crédito requerida</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-danger" id="clearLocationBtn" onclick="clearLocation()">
                    <i class="fas fa-trash"></i> Limpiar
                </button>
                <button type="button" class="btn btn-success" id="saveLocationBtn" onclick="saveLocation()" disabled>
                    <i class="fas fa-save"></i> Guardar Ubicación
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mapbox CSS -->
<link href="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css" rel="stylesheet">

<!-- Mapbox JavaScript -->
<script src="https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js"></script>

<script>
let map;
let marker;
let geocoder;

// Token de Mapbox desde configuración de Laravel
mapboxgl.accessToken = window.MAPBOX_ACCESS_TOKEN || 'pk.eyJ1IjoiYWRyaWFuZ2FyY2lhMDE5IiwiYSI6ImNtZHR3aDgyZTE2dW8ybG9obGpxYjdla2UifQ.03Yqkk_SxLuEiVgmtJn9_A';
console.log('Mapbox token configurado:', mapboxgl.accessToken);

// Inicializar cuando se abra el modal
document.addEventListener('DOMContentLoaded', function() {
    const modalElement = document.getElementById('locationModal');
    if (modalElement) {
        modalElement.addEventListener('shown.bs.modal', function () {
            if (!map) {
                initializeMapbox();
            } else {
                map.resize();
            }
        });
    }
});

function initializeMapbox() {
    try {
        // Crear mapa de Mapbox
        map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v12', // Estilo moderno
            center: [-99.1332, 19.4326], // Centro de México
            zoom: 5,
            language: 'es' // Idioma español
        });

        // Agregar controles de navegación
        map.addControl(new mapboxgl.NavigationControl(), 'top-right');

        // Agregar control de geolocalización
        map.addControl(
            new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true,
                showUserHeading: true
            }),
            'top-right'
        );

        // Evento de clic en el mapa
        map.on('click', function(e) {
            const lng = e.lngLat.lng;
            const lat = e.lngLat.lat;
            
            // Colocar marcador
            placeMarker(lng, lat);
            
            // Actualizar coordenadas
            updateCoordinates(lat, lng);
            
            // Geocodificación inversa
            reverseGeocode(lng, lat);
        });

        // Configurar búsqueda
        setupSearch();
        
        console.log('Mapbox inicializado correctamente');
    } catch (error) {
        console.error('Error al inicializar Mapbox:', error);
        alert('Error al cargar el mapa. Verifica que la API key sea válida.');
    }
}

function placeMarker(lng, lat) {
    // Remover marcador anterior
    if (marker) {
        marker.remove();
    }
    
    // Crear nuevo marcador
    marker = new mapboxgl.Marker({
        color: '#FF6B6B',
        draggable: true
    })
    .setLngLat([lng, lat])
    .addTo(map);
    
    // Evento cuando se arrastra el marcador
    marker.on('dragend', function() {
        const lngLat = marker.getLngLat();
        updateCoordinates(lngLat.lat, lngLat.lng);
        reverseGeocode(lngLat.lng, lngLat.lat);
    });
}

function updateCoordinates(lat, lng) {
    document.getElementById('latitud').value = lat.toFixed(6);
    document.getElementById('longitud').value = lng.toFixed(6);
    document.getElementById('saveLocationBtn').disabled = false;
}

function setupSearch() {
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchAddress');
    
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            const query = searchInput.value.trim();
            if (query) {
                searchLocation(query);
            }
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    searchLocation(query);
                }
            }
        });
    }
}

function searchLocation(query) {
    // Geocodificación con Mapbox
    const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${encodeURIComponent(query)}.json?access_token=${mapboxgl.accessToken}&country=MX&language=es&limit=1`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const feature = data.features[0];
                const [lng, lat] = feature.center;
                
                // Centrar mapa
                map.flyTo({
                    center: [lng, lat],
                    zoom: 15,
                    duration: 2000
                });
                
                // Colocar marcador
                placeMarker(lng, lat);
                
                // Actualizar información
                updateCoordinates(lat, lng);
                document.getElementById('direccionCompleta').value = feature.place_name;
                
            } else {
                alert('No se encontraron resultados para: ' + query);
            }
        })
        .catch(error => {
            console.error('Error en búsqueda:', error);
            alert('Error al buscar la ubicación');
        });
}

function reverseGeocode(lng, lat) {
    // Geocodificación inversa con Mapbox
    const url = `https://api.mapbox.com/geocoding/v5/mapbox.places/${lng},${lat}.json?access_token=${mapboxgl.accessToken}&language=es`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.features && data.features.length > 0) {
                const address = data.features[0].place_name;
                document.getElementById('direccionCompleta').value = address;
            }
        })
        .catch(error => {
            console.error('Error en geocodificación inversa:', error);
        });
}

function clearLocation() {
    if (marker) {
        marker.remove();
        marker = null;
    }
    
    document.getElementById('latitud').value = '';
    document.getElementById('longitud').value = '';
    document.getElementById('direccionCompleta').value = '';
    document.getElementById('searchAddress').value = '';
    document.getElementById('saveLocationBtn').disabled = true;
    
    // Volver al centro de México
    map.flyTo({
        center: [-99.1332, 19.4326],
        zoom: 5,
        duration: 1000
    });
}

function updateMapLocation(lat, lng) {
    if (map) {
        map.flyTo({
            center: [lng, lat],
            zoom: 15,
            duration: 1500
        });
        
        placeMarker(lng, lat);
        updateCoordinates(lat, lng);
    }
}
</script>
