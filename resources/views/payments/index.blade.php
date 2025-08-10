@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Panel de información de créditos -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0">
                                <i class="fas fa-coins text-warning"></i>
                                Mis Créditos de Registros
                            </h4>
                            <p class="text-muted mb-0">Administra tus créditos para crear actas sacramentales</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex justify-content-end align-items-center">
                                <div class="me-3">
                                    <h2 class="mb-0 text-primary">{{ $userCredits->available_credits }}</h2>
                                    <small class="text-muted">Créditos disponibles</small>
                                </div>
                                <div>
                                    <h6 class="mb-0">{{ $userCredits->used_credits }}</h6>
                                    <small class="text-muted">Utilizados</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($userCredits->available_credits <= 5)
                        <div class="alert alert-warning mt-3 mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>¡Pocos créditos disponibles!</strong> 
                            Considera adquirir más créditos para continuar creando actas sin interrupciones.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Encabezado de paquetes -->
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold">Paquetes de Créditos</h2>
                <p class="lead text-muted">Elige el paquete que mejor se adapte a las necesidades de tu parroquia</p>
            </div>

            <!-- Grid de paquetes -->
            <div class="row">
                @foreach($packages as $package)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0 position-relative">
                        @if($package->sort_order == 2)
                            <div class="position-absolute top-0 start-50 translate-middle">
                                <span class="badge bg-success px-3 py-2">Más Popular</span>
                            </div>
                        @endif
                        
                        <div class="card-header text-center border-0 bg-transparent pt-4">
                            <h5 class="card-title fw-bold">{{ $package->name }}</h5>
                            <div class="display-4 fw-bold text-primary">${{ number_format($package->price, 2) }}</div>
                            <p class="text-muted">{{ $package->credits_amount }} créditos</p>
                        </div>
                        
                        <div class="card-body">
                            <p class="text-muted">{{ $package->description }}</p>
                            
                            @if($package->features)
                                <ul class="list-unstyled">
                                    @foreach($package->features as $feature)
                                        <li class="mb-2">
                                            <i class="fas fa-check text-success me-2"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            
                            <div class="mt-auto">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-calculator me-1"></i>
                                    ${{ number_format($package->price_per_credit, 4) }} por crédito
                                </small>
                            </div>
                        </div>
                        
                        <div class="card-footer border-0 bg-transparent">
                            <button class="btn btn-primary w-100 purchase-btn" 
                                    data-package-id="{{ $package->id }}"
                                    data-package-name="{{ $package->name }}"
                                    data-package-price="{{ $package->formatted_price }}">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Comprar Ahora
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Información adicional -->
            <div class="row mt-5">
                <div class="col-md-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-shield-alt fa-2x text-success mb-3"></i>
                        <h5>Pago Seguro</h5>
                        <p class="text-muted">Procesamos todos los pagos de forma segura a través de Stripe</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-infinity fa-2x text-primary mb-3"></i>
                        <h5>Sin Expiración</h5>
                        <p class="text-muted">Tus créditos no expiran, úsalos cuando los necesites</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center mb-4">
                        <i class="fas fa-headset fa-2x text-info mb-3"></i>
                        <h5>Soporte 24/7</h5>
                        <p class="text-muted">Nuestro equipo está disponible para ayudarte cuando lo necesites</p>
                    </div>
                </div>
            </div>

            <!-- Botón para ver historial -->
            <div class="text-center mt-4">
                <a href="{{ route('payments.history') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-history me-2"></i>
                    Ver Historial de Pagos
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mb-0">Preparando tu pago...</p>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de pagos cargado'); // Para debug
    
    const purchaseButtons = document.querySelectorAll('.purchase-btn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    
    console.log('Botones encontrados:', purchaseButtons.length); // Para debug
    
    purchaseButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Click en botón de compra'); // Para debug
            
            const packageId = this.dataset.packageId;
            const packageName = this.dataset.packageName;
            const packagePrice = this.dataset.packagePrice;
            
            console.log('Datos del paquete:', {packageId, packageName, packagePrice}); // Para debug
            
            // Confirmar la compra
            if (!confirm(`¿Estás seguro de que deseas comprar el paquete "${packageName}" por ${packagePrice}?`)) {
                return;
            }
            
            // Mostrar loading
            loadingModal.show();
            
            // Hacer la petición de checkout
            console.log('Iniciando checkout para package:', packageId);
            
            fetch('{{ route("payments.checkout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    package_id: packageId
                })
            })
            .then(response => {
                console.log('Checkout response:', response);
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                loadingModal.hide();
                
                if (data.success) {
                    console.log('Redirigiendo a:', data.checkout_url);
                    // Redirigir a Stripe Checkout
                    window.location.href = data.checkout_url;
                } else {
                    alert('Error: ' + (data.message || 'No se pudo crear la sesión de pago'));
                }
            })
            .catch(error => {
                loadingModal.hide();
                console.error('Error:', error);
                alert('Ocurrió un error al procesar tu solicitud. Por favor, intenta de nuevo.');
            });
        });
    });
});
</script>
