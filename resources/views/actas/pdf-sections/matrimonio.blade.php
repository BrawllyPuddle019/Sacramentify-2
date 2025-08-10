<!-- Matrimonio Section -->
<div class="content">
    <p>Por medio de la presente, se hace constar que en los registros de esta parroquia se encuentra inscrito el Sacramento del Matrimonio celebrado entre los contrayentes que a continuación se mencionan:</p>
</div>

<!-- Datos de los Contrayentes -->
<div class="data-section">
    <h4>Datos de los Contrayentes</h4>
    <div class="contrayentes-grid">
        <!-- Esposo -->
        <div class="contrayente-box">
            <div class="contrayente-title">ESPOSO</div>
        @if($acta->persona)
            <div class="data-row">
                <span class="data-label">Nombre:</span>
                <span class="data-value">{{ $acta->persona->nombre }} {{ $acta->persona->paterno }} {{ $acta->persona->materno }}</span>
            </div>
            <div class="data-row">
                <span class="data-label">Nacimiento:</span>
                <span class="data-value">{{ $acta->persona->fecha_nacimiento ? \Carbon\Carbon::parse($acta->persona->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}</span>
            </div>
            @if($acta->persona->municipios)
                <div class="data-row">
                    <span class="data-label">Municipio:</span>
                    <span class="data-value">{{ $acta->persona->municipios->nombre }}</span>
                </div>
            @endif
            @if($acta->persona->municipios && $acta->persona->municipios->estado)
                <div class="data-row">
                    <span class="data-label">Estado:</span>
                    <span class="data-value">{{ $acta->persona->municipios->estado->nombre }}</span>
                </div>
            @endif
            <!-- Padres del Esposo -->
            @if($acta->padre)
                <div class="data-row">
                    <span class="data-label">Padre:</span>
                    <span class="data-value">{{ $acta->padre->nombre }} {{ $acta->padre->paterno }} {{ $acta->padre->materno }}</span>
                </div>
            @endif
            @if($acta->madre)
                <div class="data-row">
                    <span class="data-label">Madre:</span>
                    <span class="data-value">{{ $acta->madre->nombre }} {{ $acta->madre->paterno }} {{ $acta->madre->materno }}</span>
                </div>
            @endif
            @else
                <div class="data-row">
                    <span class="data-value">No se encontraron datos del esposo</span>
                </div>
            @endif
        </div>

        <!-- Esposa -->
        <div class="contrayente-box">
            <div class="contrayente-title">ESPOSA</div>
        @if($acta->persona2)
            <div class="data-row">
                <span class="data-label">Nombre:</span>
                <span class="data-value">{{ $acta->persona2->nombre }} {{ $acta->persona2->paterno }} {{ $acta->persona2->materno }}</span>
            </div>
            <div class="data-row">
                <span class="data-label">Nacimiento:</span>
                <span class="data-value">{{ $acta->persona2->fecha_nacimiento ? \Carbon\Carbon::parse($acta->persona2->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}</span>
            </div>
            @if($acta->persona2->municipios)
                <div class="data-row">
                    <span class="data-label">Municipio:</span>
                    <span class="data-value">{{ $acta->persona2->municipios->nombre }}</span>
                </div>
            @endif
            @if($acta->persona2->municipios && $acta->persona2->municipios->estado)
                <div class="data-row">
                    <span class="data-label">Estado:</span>
                    <span class="data-value">{{ $acta->persona2->municipios->estado->nombre }}</span>
                </div>
            @endif
            <!-- Padres de la Esposa -->
            @if($acta->padre1)
                <div class="data-row">
                    <span class="data-label">Padre:</span>
                    <span class="data-value">{{ $acta->padre1->nombre }} {{ $acta->padre1->paterno }} {{ $acta->padre1->materno }}</span>
                </div>
            @endif
            @if($acta->madre1)
                <div class="data-row">
                    <span class="data-label">Madre:</span>
                    <span class="data-value">{{ $acta->madre1->nombre }} {{ $acta->madre1->paterno }} {{ $acta->madre1->materno }}</span>
                </div>
            @endif
            @else
                <div class="data-row">
                    <span class="data-value">No se encontraron datos de la esposa</span>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Testigos del Matrimonio -->
@if($acta->padrino1 || $acta->madrina1)
    <div class="data-section">
        <h4>Testigos del Matrimonio</h4>
        <div class="personas-grid">
            @if($acta->padrino1)
                <div class="persona-box">
                    <div class="persona-title">Testigo 1</div>
                    <div style="font-size: 11px;">{{ $acta->padrino1->nombre }} {{ $acta->padrino1->paterno }} {{ $acta->padrino1->materno }}</div>
                </div>
            @endif
            @if($acta->madrina1)
                <div class="persona-box">
                    <div class="persona-title">Testigo 2</div>
                    <div style="font-size: 11px;">{{ $acta->madrina1->nombre }} {{ $acta->madrina1->paterno }} {{ $acta->madrina1->materno }}</div>
                </div>
            @endif
        </div>
    </div>
@endif

<!-- Información del Sacramento -->
<div class="data-section">
    <h4>Información del Sacramento</h4>
    <div class="data-row">
        <span class="data-label">Fecha:</span>
        <span class="data-value">{{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d \d\e F \d\e Y') : 'N/A' }}</span>
    </div>
    @if($acta->ermita)
        <div class="data-row">
            <span class="data-label">Lugar:</span>
            <span class="data-value">{{ $acta->ermita->nombre }}</span>
        </div>
    @endif
    @if($acta->observaciones)
        <div class="data-row">
            <span class="data-label">Observaciones:</span>
            <span class="data-value">{{ $acta->observaciones }}</span>
        </div>
    @endif
</div>

<div class="content">
    <p>El presente documento certifica que el Sacramento del Matrimonio fue celebrado conforme a las disposiciones del Derecho Canónico y se encuentra debidamente registrado en los libros parroquiales.</p>
</div>
