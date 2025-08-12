@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <!-- Header con saludo personalizado -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-header position-relative">
                <div class="welcome-content">
                    <div class="welcome-greeting">
                        <h1>¡Bienvenido, {{ auth()->user()->name }}!</h1>
                        <p class="welcome-subtitle">Gestiona los sacramentos de tu parroquia de manera eficiente</p>
                    </div>
                    <div class="welcome-info">
                        <div class="info-badge">
                            <i class="fas fa-calendar-day"></i>
                            <span>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Botón Flotante de Reporte PDF -->
                <div class="floating-report-btn">
                    <button type="button" class="btn btn-report" onclick="generateDashboardReport()" title="Descargar Reporte PDF">
                        <div class="btn-report-content">
                            <i class="fas fa-file-pdf"></i>
                            <span class="btn-report-text">Reporte</span>
                        </div>
                        <div class="btn-report-shine"></div>
                    </button>
                </div>
                
                @if($userCredits->available_credits <= 5)
                    <div class="alert alert-warning border-0 rounded-3 mt-4 mb-0" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">⚠️ Créditos Bajos</h6>
                                @if($userCredits->available_credits == 0)
                                    <p class="mb-0">No tienes créditos disponibles para crear nuevas actas. 
                                    <a href="{{ route('payments.index') }}" class="alert-link fw-semibold">Compra más créditos aquí</a>.</p>
                                @else
                                    <p class="mb-0">Te quedan solo {{ $userCredits->available_credits }} créditos. 
                                    <a href="{{ route('payments.index') }}" class="alert-link fw-semibold">Considera comprar más</a>.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas Principales -->
    <div class="stats-grid-container mb-4">
        <div class="stats-card bg-gradient-primary">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-scroll"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalActas) }}</h3>
                    <p>Total Actas</p>
                    <small class="stats-subtitle">Sacramentos registrados</small>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-success">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalPersonas) }}</h3>
                    <p>Personas</p>
                    <small class="stats-subtitle">Feligreses registrados</small>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-info">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-church"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalErmitas) }}</h3>
                    <p>Ermitas</p>
                    <small class="stats-subtitle">Lugares de culto activos</small>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-warning">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalSacerdotes) }}</h3>
                    <p>Sacerdotes</p>
                    <small class="stats-subtitle">Ministros activos</small>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-danger">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalUsuarios) }}</h3>
                    <p>Usuarios</p>
                    <small class="stats-subtitle">Usuarios del sistema</small>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-secondary">
            <div class="stats-card-body simple-layout">
                <div class="stats-content-simple">
                    <div class="simple-main-line">
                        <span class="simple-number">{{ $thisMonth }}</span>
                        <span class="simple-percentage-inline">
                            @if($growth > 0)
                                +{{ number_format($growth, 1) }}%
                            @elseif($growth < 0)
                                {{ number_format($growth, 1) }}%
                            @else
                                0.0%
                            @endif
                        </span>
                    </div>
                    <div class="simple-label">Este Mes</div>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta de Créditos -->
        <div class="stats-card {{ $userCredits->available_credits <= 5 ? 'bg-gradient-warning' : 'bg-gradient-info' }}">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $userCredits->available_credits }}</h3>
                    <p>Créditos</p>
                    @if($userCredits->available_credits <= 5)
                        <small class="text-warning-emphasis">¡Pocos créditos!</small>
                    @endif
                </div>
            </div>
            <div class="stats-card-footer">
                <a href="{{ route('payments.index') }}" class="text-decoration-none text-white-50">
                    <small><i class="fas fa-plus me-1"></i>Comprar más</small>
                </a>
            </div>
        </div>
    </div>

    <!-- Gráficos por Sacramento -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-chart-pie"></i> Distribución por Sacramento</h5>
                </div>
                <div class="chart-body">
                    <canvas id="sacramentChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-chart-line"></i> Tendencia Mensual</h5>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Detalladas -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-history"></i> Actividad Reciente</h5>
                </div>
                <div class="chart-body">
                    <div class="activity-list">
                        @foreach($recentActas as $acta)
                        <div class="activity-item">
                            <div class="activity-icon">
                                @if($acta->tipoActa && $acta->tipoActa->nombre == 'Bautizo')
                                    <i class="fas fa-baby text-primary"></i>
                                @elseif($acta->tipoActa && $acta->tipoActa->nombre == 'Matrimonio')
                                    <i class="fas fa-rings-wedding text-danger"></i>
                                @elseif($acta->tipoActa && $acta->tipoActa->nombre == 'Confirmación')
                                    <i class="fas fa-hand-paper text-warning"></i>
                                @else
                                    <i class="fas fa-scroll text-secondary"></i>
                                @endif
                            </div>
                            <div class="activity-content">
                                <h6>{{ $acta->tipoActa->nombre ?? 'Sacramento' }}</h6>
                                <p>{{ $acta->persona ? trim($acta->persona->nombre . ' ' . $acta->persona->paterno . ' ' . $acta->persona->materno) : 'Sin persona asignada' }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> {{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : 'Sin fecha' }}
                                    @if($acta->ermita)
                                        • <i class="fas fa-church"></i> {{ $acta->ermita->nombre_ermita }}
                                    @endif
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-star"></i> Top Ermitas</h5>
                </div>
                <div class="chart-body">
                    <div class="top-list">
                        @foreach($topErmitas as $index => $ermita)
                        <div class="top-item">
                            <div class="top-rank">{{ $index + 1 }}</div>
                            <div class="top-content">
                                <h6>{{ $ermita->nombre_ermita }}</h6>
                                <p>{{ $ermita->total }} registro{{ $ermita->total != 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen por Año -->
    @if($yearlyStats->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-chart-bar"></i> Resumen por Año</h5>
                </div>
                <div class="chart-body">
                    <div class="yearly-stats">
                        @foreach($yearlyStats as $yearStat)
                        <div class="year-stat">
                            <h3>{{ $yearStat->year }}</h3>
                            <p>{{ number_format($yearStat->total) }} registros</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<!-- Chart.js for Dashboard Charts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
/* Botón Flotante de Reporte PDF */
.floating-report-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
}

.btn-report {
    background: linear-gradient(135deg, #e74c3c, #c0392b);
    border: none;
    border-radius: 50px;
    padding: 12px 20px;
    color: white;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-width: 120px;
}

.btn-report:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
    background: linear-gradient(135deg, #c0392b, #a93226);
    color: white;
}

.btn-report-content {
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
    z-index: 2;
}

.btn-report-content i {
    font-size: 16px;
    animation: pulse-pdf 2s infinite;
}

.btn-report-text {
    font-weight: 600;
}

.btn-report-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s ease;
}

.btn-report:hover .btn-report-shine {
    left: 100%;
}

@keyframes pulse-pdf {
    0%, 100% {
        transform: scale(1);
        color: #fff;
    }
    50% {
        transform: scale(1.1);
        color: #ffeaa7;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .floating-report-btn {
        position: static;
        display: flex;
        justify-content: center;
        margin-top: 15px;
    }
    
    .btn-report {
        padding: 10px 16px;
        font-size: 13px;
        min-width: 100px;
    }
}
</style>

<script>
// Función para generar reporte PDF del dashboard
function generateDashboardReport() {
    // Mostrar loading
    const btn = document.querySelector('.btn-report');
    const originalContent = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generando...';
    btn.disabled = true;
    
    // Simular delay de generación
    setTimeout(() => {
        // Crear enlace de descarga
        window.location.href = '/dashboard/report/pdf';
        
        // Restaurar botón
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }, 1000);
    }, 1500);
}
</script>

<script>
$(document).ready(function() {
    // Configuración de colores para gráficos
    const colors = {
        primary: '#2c3e50',
        success: '#27ae60',
        info: '#3498db', 
        warning: '#f39c12',
        danger: '#e74c3c',
        secondary: '#95a5a6'
    };

    // Gráfico de Distribución por Sacramento
    const sacramentCtx = document.getElementById('sacramentChart').getContext('2d');
    new Chart(sacramentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Bautizos', 'Matrimonios', 'Confirmaciones'],
            datasets: [{
                data: [{{ $bautizoCount }}, {{ $matrimonioCount }}, {{ $confirmacionCount }}],
                backgroundColor: [
                    colors.primary,
                    colors.danger,
                    colors.warning
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });

    // Gráfico de Tendencia Mensual
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyStats['labels']) !!},
            datasets: [{
                label: 'Registros',
                data: {!! json_encode($monthlyStats['data']) !!},
                borderColor: colors.primary,
                backgroundColor: colors.primary + '20',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        }
    });
});
</script>
@endpush
