@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>
                    
                    <h2 class="fw-bold text-success mb-3">¡Pago Exitoso!</h2>
                    <p class="lead text-muted mb-4">
                        Tu pago ha sido procesado correctamente y tus créditos han sido añadidos a tu cuenta.
                    </p>
                    
                    <div class="bg-light rounded p-4 mb-4">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Paquete</h6>
                                <p class="fw-bold mb-0">{{ $payment->paymentPackage->name }}</p>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Créditos</h6>
                                <p class="fw-bold mb-0">{{ $payment->credits_purchased }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Monto</h6>
                                <p class="fw-bold mb-0">${{ number_format($payment->amount, 2) }}</p>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted mb-1">Fecha</h6>
                                <p class="fw-bold mb-0">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : $payment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('actas.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-alt me-2"></i>
                            Crear Nueva Acta
                        </a>
                        <a href="{{ route('payments.history') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-history me-2"></i>
                            Ver Historial de Pagos
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-link">
                            Volver al Inicio
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
