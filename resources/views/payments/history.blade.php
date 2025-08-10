@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-history me-2"></i>
                        Historial de Pagos
                    </h4>
                    <a href="{{ route('payments.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Comprar Créditos
                    </a>
                </div>
                
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Paquete</th>
                                        <th>Créditos</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>ID de Transacción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>
                                            {{ $payment->created_at->format('d/m/Y H:i') }}
                                            @if($payment->paid_at)
                                                <br><small class="text-muted">Pagado: {{ $payment->paid_at->format('d/m/Y H:i') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $payment->paymentPackage->name }}</strong>
                                            @if($payment->paymentPackage->description)
                                                <br><small class="text-muted">{{ Str::limit($payment->paymentPackage->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $payment->credits_purchased }}</span>
                                        </td>
                                        <td>
                                            <strong>${{ number_format($payment->amount, 2) }}</strong>
                                            <br><small class="text-muted">{{ strtoupper($payment->currency) }}</small>
                                        </td>
                                        <td>
                                            @switch($payment->status)
                                                @case('succeeded')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>
                                                        Exitoso
                                                    </span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i>
                                                        Pendiente
                                                    </span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times me-1"></i>
                                                        Fallido
                                                    </span>
                                                    @break
                                                @case('canceled')
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-ban me-1"></i>
                                                        Cancelado
                                                    </span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">{{ ucfirst($payment->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <code class="small">{{ Str::limit($payment->stripe_payment_intent_id, 30) }}</code>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Paginación -->
                        <div class="d-flex justify-content-center">
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No hay pagos registrados</h5>
                            <p class="text-muted">Cuando realices tu primera compra, aparecerá aquí.</p>
                            <a href="{{ route('payments.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Comprar Créditos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
