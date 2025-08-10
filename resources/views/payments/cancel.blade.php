@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-times-circle fa-4x text-warning"></i>
                    </div>
                    
                    <h2 class="fw-bold text-warning mb-3">Pago Cancelado</h2>
                    <p class="lead text-muted mb-4">
                        Has cancelado el proceso de pago. No se ha realizado ningún cargo a tu tarjeta.
                    </p>
                    
                    <div class="bg-light rounded p-4 mb-4">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Paquete</h6>
                                <p class="fw-bold mb-0">{{ $payment->paymentPackage->name }}</p>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Estado</h6>
                                <p class="fw-bold mb-0 text-warning">Cancelado</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>¿Cambiaste de opinión?</strong> Puedes intentar de nuevo cuando gustes.
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('payments.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-redo me-2"></i>
                            Intentar de Nuevo
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
