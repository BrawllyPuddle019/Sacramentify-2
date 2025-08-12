<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Dashboard - Sacramentify</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 30px 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .info-section {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 25px;
            border-left: 4px solid #3498db;
        }
        
        .info-section h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .stats-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            flex: 1;
            min-width: 140px;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card h4 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #6c757d;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section h2 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .chart-container {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        
        .data-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .data-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .pie-chart-legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
        
        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 3px;
        }
        
        .color-bautizo { background: #3498db; }
        .color-matrimonio { background: #e74c3c; }
        .color-confirmacion { background: #f39c12; }
        
        .top-list {
            list-style: none;
        }
        
        .top-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .top-item:last-child {
            border-bottom: none;
        }
        
        .rank {
            font-weight: 600;
            color: #3498db;
            width: 30px;
        }
        
        .count {
            font-weight: 600;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>📊 Reporte Dashboard - Sacramentify</h1>
        <p>Generado el {{ $fecha_generacion }} por {{ $usuario }}</p>
    </div>

    <!-- Información General -->
    <div class="info-section">
        <h3>ℹ️ Información del Reporte</h3>
        <p><strong>Usuario:</strong> {{ $usuario }}</p>
        <p><strong>Fecha de Generación:</strong> {{ $fecha_generacion }}</p>
        <p><strong>Tipo de Reporte:</strong> Estadísticas Generales del Dashboard</p>
    </div>

    <!-- Estadísticas Principales -->
    <div class="section">
        <h2>📈 Estadísticas Principales</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h4>{{ number_format($totalActas) }}</h4>
                <p>Total Actas</p>
            </div>
            <div class="stat-card">
                <h4>{{ number_format($totalPersonas) }}</h4>
                <p>Personas</p>
            </div>
            <div class="stat-card">
                <h4>{{ number_format($totalSacerdotes) }}</h4>
                <p>Sacerdotes</p>
            </div>
            <div class="stat-card">
                <h4>{{ number_format($totalErmitas) }}</h4>
                <p>Ermitas</p>
            </div>
            <div class="stat-card">
                <h4>{{ number_format($totalUsuarios) }}</h4>
                <p>Usuarios</p>
            </div>
        </div>
    </div>

    <!-- Distribución por Sacramento -->
    <div class="section">
        <h2>⛪ Distribución por Sacramento</h2>
        <div class="chart-container">
            <div class="chart-title">Tipos de Sacramento Registrados</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Sacramento</th>
                        <th>Cantidad</th>
                        <th>Porcentaje</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = $bautizoCount + $matrimonioCount + $confirmacionCount;
                    @endphp
                    <tr>
                        <td>🫧 Bautizos</td>
                        <td>{{ number_format($bautizoCount) }}</td>
                        <td>{{ $total > 0 ? number_format(($bautizoCount / $total) * 100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td>💒 Matrimonios</td>
                        <td>{{ number_format($matrimonioCount) }}</td>
                        <td>{{ $total > 0 ? number_format(($matrimonioCount / $total) * 100, 1) : 0 }}%</td>
                    </tr>
                    <tr>
                        <td>✨ Confirmaciones</td>
                        <td>{{ number_format($confirmacionCount) }}</td>
                        <td>{{ $total > 0 ? number_format(($confirmacionCount / $total) * 100, 1) : 0 }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Ermitas -->
    <div class="section">
        <h2>🏛️ Top Ermitas</h2>
        <div class="chart-container">
            <div class="chart-title">Ermitas con Más Registros</div>
            @if($topErmitas->count() > 0)
                <ul class="top-list">
                    @foreach($topErmitas as $index => $ermita)
                    <li class="top-item">
                        <div>
                            <span class="rank">{{ $index + 1 }}.</span>
                            {{ $ermita->nombre_ermita }}
                        </div>
                        <span class="count">{{ number_format($ermita->total) }} registro{{ $ermita->total != 1 ? 's' : '' }}</span>
                    </li>
                    @endforeach
                </ul>
            @else
                <p style="text-align: center; color: #6c757d; padding: 20px;">No hay datos de ermitas disponibles</p>
            @endif
        </div>
    </div>

    <!-- Estadísticas Mensuales -->
    <div class="section page-break">
        <h2>📅 Tendencia Mensual</h2>
        <div class="chart-container">
            <div class="chart-title">Registros por Mes (Últimos 6 Meses)</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mes</th>
                        <th>Registros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlyStats['labels'] as $index => $month)
                    <tr>
                        <td>{{ $month }}</td>
                        <td>{{ number_format($monthlyStats['data'][$index]) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Estadísticas Anuales -->
    @if($yearlyStats->count() > 0)
    <div class="section">
        <h2>📊 Resumen por Año</h2>
        <div class="chart-container">
            <div class="chart-title">Registros Anuales</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Total de Registros</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($yearlyStats as $yearStat)
                    <tr>
                        <td>{{ $yearStat->year }}</td>
                        <td>{{ number_format($yearStat->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Reporte generado por Sacramentify - Sistema de Gestión de Sacramentos</p>
        <p>{{ $fecha_generacion }}</p>
    </div>
</body>
</html>
