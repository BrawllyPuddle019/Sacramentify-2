<!-- Test simple para debug de Mapbox -->
<div class="alert alert-info">
    <h4>Test de Mapbox</h4>
    <button type="button" class="btn btn-primary" onclick="testMapbox()">
        Test Mapbox
    </button>
    <div id="testResult" class="mt-2"></div>
</div>

<script>
function testMapbox() {
    const result = document.getElementById('testResult');
    let messages = [];
    
    // Verificar que Mapbox esté cargado
    if (typeof mapboxgl === 'undefined') {
        messages.push('<div class="alert alert-danger">❌ Mapbox no se cargó correctamente</div>');
    } else {
        messages.push('<div class="alert alert-success">✅ Mapbox JavaScript cargado</div>');
    }
    
    // Verificar token
    const token = window.MAPBOX_ACCESS_TOKEN;
    console.log('Token detectado:', token);
    
    if (!token || token === 'YOUR_MAPBOX_ACCESS_TOKEN_HERE' || token === '') {
        messages.push('<div class="alert alert-danger">❌ Token de Mapbox no configurado</div>');
        messages.push(`<div class="alert alert-info">Token actual: "${token}"</div>`);
    } else {
        messages.push(`<div class="alert alert-success">✅ Token configurado: ${token.substring(0, 20)}...</div>`);
        
        // Test de conexión
        try {
            mapboxgl.accessToken = token;
            messages.push('<div class="alert alert-success">✅ Token asignado a Mapbox</div>');
        } catch (error) {
            messages.push(`<div class="alert alert-danger">❌ Error al asignar token: ${error.message}</div>`);
        }
    }
    
    result.innerHTML = messages.join('');
}

// Auto-test al cargar
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(testMapbox, 1000);
});
</script>
